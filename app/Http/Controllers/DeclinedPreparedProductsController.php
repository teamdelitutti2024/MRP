<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeclinedPreparedProduct;
use App\Models\DeclinedPreparedProductLog;
use App\Models\PreparedProduct;
use App\SuppliesHelper;
use App\Models\Stock;

class DeclinedPreparedProductsController extends Controller
{
    public function index()
    {
        $declined_prepared_products = DeclinedPreparedProduct::orderBy('created_at', 'desc')->get();
        return view('declined_prepared_products.index', compact('declined_prepared_products'));
    }

    public function add()
    {
        $prepared_products = PreparedProduct::orderBy('name', 'asc')->get();

        // Iterate between prepared products to only show those that have ingredients
        $prepared_products = $prepared_products->filter(function($element) {
            return $element->ingredients->isNotEmpty() ? true : false;
        });

        return view('declined_prepared_products.add', compact('prepared_products'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'details' => '',
        ]);

        if(isset($validated_data['details'])) {
            for($i = 0; $i < count($validated_data['details']['quantity']); $i++) {
                // Get related prepared product
                $prepared_product = PreparedProduct::find($validated_data['details']['id'][$i]);

                // Create declined prepared product
                $declined_prepared_product = DeclinedPreparedProduct::create([
                    'quantity' => $validated_data['details']['quantity'][$i],
                    'prepared_product_name' => $prepared_product->name,
                    'comments' => $validated_data['details']['comment'][$i],
                    'prepared_product_id' => $prepared_product->id,
                    'responsible_id' => auth()->user()->id,
                ]);

                // Get required supplies for this prepared product
                $supplies = SuppliesHelper::calculate_prepared_products_production_supplies($validated_data['details']['id'][$i], $validated_data['details']['quantity'][$i]);

                $prepared_product_log = array();

                foreach($supplies as $element) {
                    // Get related stock
                    $supply_stock = Stock::where('supply_id', $element['supply_id'])->where('supply_location_id', $element['supply_location_id'])->first();
        
                    $supply_stock->quantity = $supply_stock->quantity - $element['quantity'];
                    $supply_stock->save();

                    // Save current element in prepared product log array
                    $prepared_product_log[] = array(
                        'supply_id' => $element['supply_id'],
                        'supply_key' => $element['supply_key'],
                        'supply' => $element['supply'],
                        'supply_location_id' => $element['supply_location_id'],
                        'supply_location' => $element['supply_location'],
                        'quantity' => $element['quantity'],
                        'measurement_unit_id' => $element['measurement_unit_id'],
                        'measure' => $element['measure'],
                        'average_cost' => $element['average_cost'],
                        'declined_pp_id' => $declined_prepared_product->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                }

                // Save production log
                DeclinedPreparedProductLog::insert($prepared_product_log);
            }
        } 

        request()->session()->flash('alertmessage', [
            'message' => 'Merma de preparados agregada',
            'type' => 'success',
        ]);

        return redirect('/declined_prepared_products');
    }

    public function detail(DeclinedPreparedProduct $declined_prepared_product)
    {
        return view('declined_prepared_products.detail', compact('declined_prepared_product'));
    }

    public function revert(DeclinedPreparedProduct $declined_prepared_product)
    {
        foreach($declined_prepared_product->prepared_product_supplies as $element) {
            // Get related stock
            $supply_stock = Stock::where('supply_id', $element->supply_id)->where('supply_location_id', $element->supply_location_id)->first();

            $supply_stock->quantity = $supply_stock->quantity + $element->quantity;
            $supply_stock->save();
        }

        $declined_prepared_product->status = false;
        $declined_prepared_product->reversed_responsible_id = auth()->user()->id;
        $declined_prepared_product->save();

        request()->session()->flash('alertmessage', [
            'message' => 'Merma de preparado revertida',
            'type' => 'success',
        ]);

        return redirect('/declined_prepared_products');
    }
}
