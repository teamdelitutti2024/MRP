<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductProduction;
use App\Models\ProductProductionDetail;
use App\Models\StockLevel1;
use App\Models\StockLevel1Detail;
use App\Models\StockLevel2;
use App\Models\StockLevel2Detail;
use App\Models\ProductSize;
use App\Models\Stock;
use App\Models\ProductProductionBakeBreadLog;
use App\ProductionHelper;
use App\Exports\ProductionProjectionExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductsProductionController extends Controller
{
    public function index()
    {
        $products_production = ProductProduction::orderBy('created_at', 'desc')->get();
        return view('products_production.index', compact('products_production'));
    }

    public function add()
    {
        $product_sizes = ProductSize::orderBy('name', 'asc')->get();
        return view('products_production.add', compact('product_sizes'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $products_production = ProductProduction::create([
            'responsible_id' => auth()->user()->id,
        ]);

        $products_production_detail = $bake_breads = array();

        if(isset($data['details'])) {
            for($i = 0; $i < count($data['details']['quantity']); $i++) {
                $product_size = ProductSize::find($data['details']['id'][$i]);

                foreach($product_size->bake_breads as $element) {
                    // Check if bake bread size already exists on bake breads array
                    if(array_key_exists($element->bake_bread_size_id, $bake_breads)) {
                        // Update accumulated quantity
                        $bake_breads[$element->bake_bread_size_id]['quantity'] = $bake_breads[$element->bake_bread_size_id]['quantity'] + ($element->quantity * $data['details']['quantity'][$i]);
                    }
                    else {
                        // Add related bake bread size data to bake breads array
                        $bake_breads[$element->bake_bread_size_id] = array(
                            'quantity' => $element->quantity * $data['details']['quantity'][$i],
                            'bake_bread_size_id' => $element->bake_bread_size_id,
                            'production_id' => $products_production->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                    }
                }

                // Insert element to products production array
                $products_production_detail[] = array(
                    'product_name' => $product_size->product->name,
                    'product_size_name' => $product_size->name,
                    'quantity' => $data['details']['quantity'][$i],
                    'price' => $product_size->price,
                    'product_id' => $product_size->product_id,
                    'product_size_id' => $product_size->id,
                    'products_production_id' => $products_production->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );

                // Get related stock level 1
                $stock_level_1 = StockLevel1::where('product_size_id', $product_size->id)->first();

                // If stock level 1 exist then update quantity
                if($stock_level_1) {
                    // Save last quantity
                    $last_quantity = $stock_level_1->quantity;

                    $stock_level_1->quantity = $stock_level_1->quantity + $data['details']['quantity'][$i];
                    $stock_level_1->save();
                }
                else {
                    // Set last quantity to 0
                    $last_quantity = 0;

                    // Insert stock level 1 row
                    $stock_level_1 = StockLevel1::create([
                        'quantity' => $data['details']['quantity'][$i],
                        'product_id' => $product_size->product_id,
                        'product_size_id' => $product_size->id,
                    ]);
                }

                // Insert stock level 1 detail row
                StockLevel1Detail::create([
                    'quantity' => $data['details']['quantity'][$i],
                    'last_quantity' => $last_quantity,
                    'valid_until' => date('Y-m-d', strtotime('+2 days')),
                    'reason' => 'PRODUCCIÓN',
                    'stock_level_1_id' => $stock_level_1->id,
                    'responsible_id' => auth()->user()->id,
                ]);
            }
        }

        // Process bake breads
        foreach($bake_breads as $bake_bread) {
            // Get stock level 2
            $stock_level_2 = StockLevel2::where('bake_bread_size_id', $bake_bread['bake_bread_size_id'])->first();

            // If stock level 2 exist then update quantity
            if($stock_level_2) {
                // Save last quantity
                $last_quantity = $stock_level_2->quantity;

                $stock_level_2->quantity = $stock_level_2->quantity - $bake_bread['quantity'];
                $stock_level_2->save();
            }
            else {
                // Set last quantity to 0
                $last_quantity = 0;

                // Insert stock level 2 row
                $stock_level_2 = StockLevel2::create([
                    'quantity' => $bake_bread['quantity'] * -1,
                    'bake_bread_size_id' => $bake_bread['bake_bread_size_id'],
                ]);
            }

            // Insert stock level 2 detail row
            StockLevel2Detail::create([
                'quantity' => $bake_bread['quantity'] * -1,
                'last_quantity' => $last_quantity,
                'reason' => 'TOMADO PARA PRODUCCIÓN DE PRODUCTOS',
                'stock_level_2_id' => $stock_level_2->id,
                'responsible_id' => auth()->user()->id,
            ]);
        }

        // Insert products production details
        ProductProductionDetail::insert($products_production_detail);

        // Insert products production bake breads log
        ProductProductionBakeBreadLog::insert($bake_breads);

        // Process production
        ProductionHelper::process_production(1, $data['details'], $products_production->id);

        request()->session()->flash('alertmessage', [
            'message' => 'Producción de productos finalizada',
            'type' => 'success',
        ]);

        return redirect('/products_production');
    }

    public function detail(ProductProduction $production)
    {
        $products = array();

        foreach($production->production_details as $element) {
            $products['id'][] = $element->product_size_id;
            $products['quantity'][] = $element->quantity;
        }

        return view('products_production.detail', compact('production'));
    }
    
    public function download_projection(ProductProduction $production)
    {
        $products = array();

        foreach($production->production_details as $element) {
            $products['id'][] = $element->product_size_id;
            $products['quantity'][] = $element->quantity;
        }

        return Excel::download(new ProductionProjectionExport($production, $products, 1), 'proyección_producción_productos_' . $production->id . '.xlsx');
    }

    public function revert(ProductProduction $production)
    {
        // Revert supplies
        foreach($production->production_supplies as $element) {
            // Get related stock
            $supply_stock = Stock::where('supply_id', $element->supply_id)->where('supply_location_id', $element->supply_location_id)->first();

            $supply_stock->quantity = $supply_stock->quantity + $element->quantity;
            $supply_stock->save();
        }

        // Revert products
        foreach($production->production_details as $element) {
            // Get related stock level 1
            $stock_level_1 = StockLevel1::where('product_size_id', $element->product_size_id)->first();

            $stock_level_1->quantity = $stock_level_1->quantity - $element->quantity;
            $stock_level_1->save();

            // Insert stock level 1 detail row
            StockLevel1Detail::create([
                'quantity' => $element->quantity * -1,
                'last_quantity' => $stock_level_1->quantity + $element->quantity,
                'reason' => 'REVERSIÓN DE PRODUCCIÓN',
                'stock_level_1_id' => $stock_level_1->id,
                'responsible_id' => auth()->user()->id,
            ]);
        }

        // Revert bake breads
        foreach($production->production_bake_breads as $element) {
            // Get stock level 2
            $stock_level_2 = StockLevel2::where('bake_bread_size_id', $element->bake_bread_size_id)->first();

            $stock_level_2->quantity = $stock_level_2->quantity + $element->quantity;
            $stock_level_2->save();

            // Insert stock level 2 detail row
            StockLevel2Detail::create([
                'quantity' => $element->quantity,
                'last_quantity' => $stock_level_2->quantity - $element->quantity,
                'reason' => 'REVERSIÓN DE PRODUCCIÓN DESDE PRODUCTOS',
                'stock_level_2_id' => $stock_level_2->id,
                'responsible_id' => auth()->user()->id,
            ]);
        }

        $production->status = false;
        $production->reversed_responsible_id = auth()->user()->id;
        $production->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Producción de productos revertida',
            'type' => 'success',
        ]);

        return redirect('/products_production');
    }
}
