<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BakeBreadSize;
use App\Models\BakeBreadIngredient;
use App\Models\BakeBreadSizeBakeBread;
use App\Models\BakeBreadSizePreparedData;
use App\Models\BakeBreadSizeResource;
use App\Models\Supply;
use App\Models\SupplyLocation;
use App\Models\PreparedProduct;
use App\Models\Resource;
use App\BakeBreadsHelper;

class BakeBreadSizesController extends Controller
{
    public function index()
    {
        $bake_bread_sizes = BakeBreadSize::orderBy('name', 'asc')->get();
        return view('bake_bread_sizes.index', compact('bake_bread_sizes'));
    }

    public function add()
    {
        $supplies = Supply::orderBy('name', 'asc')->get();
        $supply_locations = SupplyLocation::where('location', '<>', 'ALMACEN')->orderBy('location', 'asc')->get();
        $bake_bread_sizes = BakeBreadSize::orderBy('name', 'asc')->get();
        $prepared_products = PreparedProduct::orderBy('name', 'asc')->get();
        $resources = Resource::orderBy('name', 'asc')->get();

        return view('bake_bread_sizes.add', compact('supplies', 'supply_locations', 'bake_bread_sizes', 'prepared_products', 'resources'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'complexity' => 'required',
            'ingredients' => '',
            'bake_bread_sizes' => '',
            'prepared_products' => '',
            'resources' => '',
        ]);

        $bake_bread_size = $this->store_callback($validated_data['name']);

        if($bake_bread_size) {
            request()->session()->flash('alertmessage', [
                'message' => 'El tamaño ' . $bake_bread_size->name . ' ya se encuentra registrado',
                'type' => 'error',
            ]);
    
            return back();
        }

        $validated_data['complexity'] = $validated_data['complexity'] == 0 ? null : $validated_data['complexity'];

        $bake_bread_size = BakeBreadSize::create([
            'name' => $validated_data['name'],
            'complexity' => $validated_data['complexity'],
        ]);

        // Build new key to be updated
        $bake_bread_size_key = 'BA-' . str_pad($bake_bread_size->id, 3, '0', STR_PAD_LEFT);

        // Update bake bread size key
        $bake_bread_size->bake_bread_size_key = $bake_bread_size_key;
        $bake_bread_size->saveQuietly();

        // Create new ingredients if exist
        if(isset($validated_data['ingredients'])) {
            for($i = 0; $i < count($validated_data['ingredients']['quantity']); $i++) {
                BakeBreadIngredient::create([
                    'quantity' => $validated_data['ingredients']['quantity'][$i],
                    'quantity_to_produce' => $validated_data['ingredients']['quantity_to_produce'][$i],
                    'supply_id' => $validated_data['ingredients']['supply_id'][$i],
                    'bake_bread_size_id' => $bake_bread_size->id,
                    'measurement_unit_id' => $validated_data['ingredients']['measurement_unit_id'][$i],
                    'supply_location_id' => $validated_data['ingredients']['supply_location_id'][$i]
                ]);
            }
        }

        // Create new bake bread sizes if exist
        if(isset($validated_data['bake_bread_sizes'])) {
            for($i = 0; $i < count($validated_data['bake_bread_sizes']['quantity']); $i++) {
                BakeBreadSizeBakeBread::create([
                    'quantity' => $validated_data['bake_bread_sizes']['quantity'][$i],
                    'bake_bread_size_id' => $bake_bread_size->id,
                    'related_bake_bread_size_id' => $validated_data['bake_bread_sizes']['related_bake_bread_size_id'][$i],
                ]);
            }
        }

        // Create new prepared products if exist
        if(isset($validated_data['prepared_products'])) {
            for($i = 0; $i < count($validated_data['prepared_products']['quantity']); $i++) {
                BakeBreadSizePreparedData::create([
                    'quantity' => $validated_data['prepared_products']['quantity'][$i],
                    'bake_bread_size_id' => $bake_bread_size->id,
                    'prepared_product_id' => $validated_data['prepared_products']['prepared_product_id'][$i],
                ]);
            }
        }

        // Create new resources if exist
        if(isset($validated_data['resources'])) {
            for($i = 0; $i < count($validated_data['resources']['quantity_to_produce']); $i++) {
                BakeBreadSizeResource::create([
                    'production_time' => $validated_data['resources']['production_time'][$i],
                    'quantity_to_produce' => $validated_data['resources']['quantity_to_produce'][$i],
                    'bake_bread_size_id' => $bake_bread_size->id,
                    'resource_id' => $validated_data['resources']['resource_id'][$i],
                ]);
            }
        }

        request()->session()->flash('alertmessage', [
            'message' => 'Tamaño de base agregado',
            'type' => 'success',
        ]);

        return redirect('/bake_bread_sizes');
    }

    public function get_row()
    {
        $bake_bread_size = BakeBreadSize::find(request('id'));

        // Calculate bake bread size cost
        $bake_bread_size->cost = BakeBreadsHelper::calculate_cost($bake_bread_size->id);

        return response()->json($bake_bread_size);
    }

    public function get_row_v2()
    {
        $bake_bread_size = BakeBreadSize::find(request('id'));
        return response()->json($bake_bread_size);
    }

    public function edit(BakeBreadSize $bake_bread_size)
    {
        $supplies = Supply::orderBy('name', 'asc')->get();
        $supply_locations = SupplyLocation::where('location', '<>', 'ALMACEN')->orderBy('location', 'asc')->get();
        $bake_bread_sizes = BakeBreadSize::orderBy('name', 'asc')->get();
        $prepared_products = PreparedProduct::orderBy('name', 'asc')->get();
        $resources = Resource::orderBy('name', 'asc')->get();

        $bake_breads_data = $bake_bread_size->bake_bread_sizes;
        $prepared_products_data = $bake_bread_size->prepared_product_data;
        $resources_data = $bake_bread_size->resources;

        return view('bake_bread_sizes.edit', compact('bake_bread_size', 'supplies', 'supply_locations', 'bake_bread_sizes', 'prepared_products', 'resources', 'bake_breads_data', 'prepared_products_data', 'resources_data'));
    }

    public function update(Request $request, BakeBreadSize $bake_bread_size)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'complexity' => 'required',
            'ingredients' => '',
            'bake_bread_sizes' => '',
            'prepared_products' => '',
            'resources' => '',
        ]);

        $existing_bake_bread_size = $this->update_callback($bake_bread_size->id, $validated_data['name']);

        if($existing_bake_bread_size) {
            request()->session()->flash('alertmessage', [
                'message' => 'El tamaño ' . $existing_bake_bread_size->name . ' ya se encuentra registrado',
                'type' => 'error',
            ]);
    
            return back();
        }

        $validated_data['complexity'] = $validated_data['complexity'] == 0 ? null : $validated_data['complexity'];

        $bake_bread_size->name = $validated_data['name'];
        $bake_bread_size->complexity = $validated_data['complexity'];
        $bake_bread_size->save();

        // Delete old ingredients
        BakeBreadIngredient::where('bake_bread_size_id', $bake_bread_size->id)->delete();

        // Delete old bake bread sizes
        BakeBreadSizeBakeBread::where('bake_bread_size_id', $bake_bread_size->id)->delete();

        // Delete old prepared products
        BakeBreadSizePreparedData::where('bake_bread_size_id', $bake_bread_size->id)->delete();

        // Delete old resources
        BakeBreadSizeResource::where('bake_bread_size_id', $bake_bread_size->id)->delete();

        // Create new ingredients if exist
        if(isset($validated_data['ingredients'])) {
            for($i = 0; $i < count($validated_data['ingredients']['quantity']); $i++) {
                BakeBreadIngredient::create([
                    'quantity' => $validated_data['ingredients']['quantity'][$i],
                    'quantity_to_produce' => $validated_data['ingredients']['quantity_to_produce'][$i],
                    'supply_id' => $validated_data['ingredients']['supply_id'][$i],
                    'bake_bread_size_id' => $bake_bread_size->id,
                    'measurement_unit_id' => $validated_data['ingredients']['measurement_unit_id'][$i],
                    'supply_location_id' => $validated_data['ingredients']['supply_location_id'][$i]
                ]);
            }
        }

        // Create new bake bread sizes if exist
        if(isset($validated_data['bake_bread_sizes'])) {
            for($i = 0; $i < count($validated_data['bake_bread_sizes']['quantity']); $i++) {
                BakeBreadSizeBakeBread::create([
                    'quantity' => $validated_data['bake_bread_sizes']['quantity'][$i],
                    'bake_bread_size_id' => $bake_bread_size->id,
                    'related_bake_bread_size_id' => $validated_data['bake_bread_sizes']['related_bake_bread_size_id'][$i],
                ]);
            }
        }

        // Create new prepared products if exist
        if(isset($validated_data['prepared_products'])) {
            for($i = 0; $i < count($validated_data['prepared_products']['quantity']); $i++) {
                BakeBreadSizePreparedData::create([
                    'quantity' => $validated_data['prepared_products']['quantity'][$i],
                    'bake_bread_size_id' => $bake_bread_size->id,
                    'prepared_product_id' => $validated_data['prepared_products']['prepared_product_id'][$i],
                ]);
            }
        }

        // Create new resources if exist
        if(isset($validated_data['resources'])) {
            for($i = 0; $i < count($validated_data['resources']['quantity_to_produce']); $i++) {
                BakeBreadSizeResource::create([
                    'production_time' => $validated_data['resources']['production_time'][$i],
                    'quantity_to_produce' => $validated_data['resources']['quantity_to_produce'][$i],
                    'bake_bread_size_id' => $bake_bread_size->id,
                    'resource_id' => $validated_data['resources']['resource_id'][$i],
                ]);
            }
        }

        request()->session()->flash('alertmessage', [
            'message' => 'Tamaño de base actualizado',
            'type' => 'success',
        ]);

        return back();
    }

    private function store_callback($name)
    {
        // Search bake bread size by name
        $bake_bread_size = BakeBreadSize::where('name', $name)->first();
        return $bake_bread_size;
    }

    private function update_callback($id, $name)
    {
        // Search bake bread size by name from different id
        $bake_bread_size = BakeBreadSize::where('id', '<>', $id)->where('name', $name)->first();
        return $bake_bread_size;
    }
}
