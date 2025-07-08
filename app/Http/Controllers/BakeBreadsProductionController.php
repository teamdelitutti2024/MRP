<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BakeBreadProduction;
use App\Models\BakeBreadProductionDetail;
use App\Models\StockLevel2;
use App\Models\StockLevel2Detail;
use App\Models\BakeBreadSize;
use App\Models\Stock;
use App\ProductionHelper;
use App\Exports\ProductionProjectionExport;
use Maatwebsite\Excel\Facades\Excel;

class BakeBreadsProductionController extends Controller
{
    public function index()
    {
        $bake_breads_production = BakeBreadProduction::orderBy('created_at', 'desc')->get();
        return view('bake_breads_production.index', compact('bake_breads_production'));
    }

    public function add()
    {
        $bake_bread_sizes = BakeBreadSize::orderBy('name', 'asc')->get();
        return view('bake_breads_production.add', compact('bake_bread_sizes'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $bake_breads_production = BakeBreadProduction::create([
            'responsible_id' => auth()->user()->id,
        ]);

        $bake_breads_production_detail = $bake_breads = array();

        if(isset($data['details'])) {
            for($i = 0; $i < count($data['details']['quantity']); $i++) {
                $bake_bread_size = BakeBreadSize::find($data['details']['id'][$i]);

                foreach($bake_bread_size->bake_bread_sizes as $element) {
                    // Check if related bake bread size already exists on bake breads array
                    if(array_key_exists($element->related_bake_bread_size_id, $bake_breads)) {
                        // Update accumulated quantity
                        $bake_breads[$element->related_bake_bread_size_id]['quantity'] = $bake_breads[$element->related_bake_bread_size_id]['quantity'] + ($element->quantity * $data['details']['quantity'][$i]);
                    }
                    else {
                        // Add related bake bread size data to bake breads array
                        $bake_breads[$element->related_bake_bread_size_id] = array(
                            'quantity' => $element->quantity * $data['details']['quantity'][$i],
                            'bake_bread_size_id' => $element->related_bake_bread_size_id,
                            'production_id' => $bake_breads_production->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                    }
                }

                // Insert element to bake breads production array
                $bake_breads_production_detail[] = array(
                    'bake_bread_size_name' => $bake_bread_size->name,
                    'quantity' => $data['details']['quantity'][$i],
                    'bake_bread_size_id' => $bake_bread_size->id,
                    'bake_breads_production_id' => $bake_breads_production->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );

                // Get related stock level 2
                $stock_level_2 = StockLevel2::where('bake_bread_size_id', $bake_bread_size->id)->first();

                // If stock level 2 exist then update quantity
                if($stock_level_2) {
                    // Save last quantity
                    $last_quantity = $stock_level_2->quantity;

                    $stock_level_2->quantity = $stock_level_2->quantity + $data['details']['quantity'][$i];
                    $stock_level_2->save();
                }
                else {
                    // Set last quantity to 0
                    $last_quantity = 0;

                    // Insert stock level 2 row
                    $stock_level_2 = StockLevel2::create([
                        'quantity' => $data['details']['quantity'][$i],
                        'bake_bread_size_id' => $bake_bread_size->id,
                    ]);
                }

                // Insert stock level 2 detail row
                StockLevel2Detail::create([
                    'quantity' => $data['details']['quantity'][$i],
                    'last_quantity' => $last_quantity,
                    'valid_until' => date('Y-m-d', strtotime('+2 days')),
                    'reason' => 'PRODUCCIÓN',
                    'stock_level_2_id' => $stock_level_2->id,
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
                'reason' => 'TOMADO PARA PRODUCCIÓN DE BASES',
                'stock_level_2_id' => $stock_level_2->id,
                'responsible_id' => auth()->user()->id,
            ]);
        }

        // Insert bake breads production details
        BakeBreadProductionDetail::insert($bake_breads_production_detail);

        // Process production
        ProductionHelper::process_production(2, $data['details'], $bake_breads_production->id);

        request()->session()->flash('alertmessage', [
            'message' => 'Producción de bases finalizada',
            'type' => 'success',
        ]);

        return redirect('/bake_breads_production');
    }

    public function detail(BakeBreadProduction $production)
    {
        $bake_breads = array();

        foreach($production->production_details as $element) {
            $bake_breads['id'][] = $element->bake_bread_size_id;
            $bake_breads['quantity'][] = $element->quantity;
        }

        return view('bake_breads_production.detail', compact('production'));
    }

    public function download_projection(BakeBreadProduction $production)
    {
        $bake_breads = array();

        foreach($production->production_details as $element) {
            $bake_breads['id'][] = $element->bake_bread_size_id;
            $bake_breads['quantity'][] = $element->quantity;
        }

        return Excel::download(new ProductionProjectionExport($production, $bake_breads, 2), 'proyección_producción_bases_' . $production->id . '.xlsx');
    }

    public function revert(BakeBreadProduction $production)
    {
        // Revert supplies
        foreach($production->production_supplies as $element) {
            // Get related stock
            $supply_stock = Stock::where('supply_id', $element->supply_id)->where('supply_location_id', $element->supply_location_id)->first();

            $supply_stock->quantity = $supply_stock->quantity + $element->quantity;
            $supply_stock->save();
        }

        // Revert bake breads
        foreach($production->production_details as $element) {
            // Get related stock level 2
            $stock_level_2 = StockLevel2::where('bake_bread_size_id', $element->bake_bread_size_id)->first();

            $stock_level_2->quantity = $stock_level_2->quantity - $element->quantity;
            $stock_level_2->save();

            // Insert stock level 2 detail row
            StockLevel2Detail::create([
                'quantity' => $element->quantity * -1,
                'last_quantity' => $stock_level_2->quantity + $element->quantity,
                'reason' => 'REVERSIÓN DE PRODUCCIÓN',
                'stock_level_2_id' => $stock_level_2->id,
                'responsible_id' => auth()->user()->id,
            ]);
        }

        $production->status = false;
        $production->reversed_responsible_id = auth()->user()->id;
        $production->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Producción de bases revertida',
            'type' => 'success',
        ]);

        return redirect('/bake_breads_production');
    }
}
