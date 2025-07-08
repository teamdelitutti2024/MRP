<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreparedProduct;
use App\Models\PreparedProductIngredient;
use App\Models\Supply;
use App\Models\SupplyLocation;
use App\Models\Resource;
use App\Models\PreparedProductResource;
use App\PreparedProductsHelper;

class PreparedProductsController extends Controller
{
    public function index()
    {
        $prepared_products = PreparedProduct::orderBy('name', 'asc')->get();
        return view('prepared_products.index', compact('prepared_products'));
    }

    public function add()
    {
        $supplies = Supply::orderBy('name', 'asc')->get();
        $supply_locations = SupplyLocation::where('location', '<>', 'ALMACEN')->orderBy('location', 'asc')->get();
        $resources = Resource::orderBy('name', 'asc')->get();

        return view('prepared_products.add', compact('supplies', 'supply_locations', 'resources'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'ingredients' => '',
            'resources' => '',
        ]);

        $prepared_product = $this->store_callback($validated_data['name']);

        if($prepared_product) {
            request()->session()->flash('alertmessage', [
                'message' => 'El preparado ya se encuentra registrado',
                'type' => 'error',
            ]);
    
            return back()->withInput();
        }

        $prepared_product = PreparedProduct::create([
            'name' => $validated_data['name'],
        ]);

        // Create new ingredients if exist
        if(isset($validated_data['ingredients'])) {
            for($i = 0; $i < count($validated_data['ingredients']['quantity']); $i++) {
                PreparedProductIngredient::create([
                    'quantity' => $validated_data['ingredients']['quantity'][$i],
                    'quantity_to_produce' => $validated_data['ingredients']['quantity_to_produce'][$i],
                    'prepared_product_id' => $prepared_product->id,
                    'supply_id' => $validated_data['ingredients']['supply_id'][$i],
                    'measurement_unit_id' => $validated_data['ingredients']['measurement_unit_id'][$i],
                    'supply_location_id' => $validated_data['ingredients']['supply_location_id'][$i]
                ]);
            }
        }

        // Create new resources if exist
        if(isset($validated_data['resources'])) {
            for($i = 0; $i < count($validated_data['resources']['quantity_to_produce']); $i++) {
                PreparedProductResource::create([
                    'production_time' => $validated_data['resources']['production_time'][$i],
                    'quantity_to_produce' => $validated_data['resources']['quantity_to_produce'][$i],
                    'prepared_product_id' => $prepared_product->id,
                    'resource_id' => $validated_data['resources']['resource_id'][$i],
                ]);
            }
        }

        // Build new key to be updated
        $product_key = 'ST-' . str_pad($prepared_product->id, 3, '0', STR_PAD_LEFT);

        // Update prepared product key
        $prepared_product->product_key = $product_key;
        $prepared_product->saveQuietly();

        request()->session()->flash('alertmessage', [
            'message' => 'Preparado agregado',
            'type' => 'success',
        ]);

        return redirect('/prepared_products');
    }

    public function get_row()
    {
        $prepared_product = PreparedProduct::find(request('id'));

        // Calculate prepared product cost
        $prepared_product->cost = PreparedProductsHelper::calculate_cost($prepared_product->id);

        return response()->json($prepared_product);
    }

    public function edit(PreparedProduct $prepared_product)
    {
        $supplies = Supply::orderBy('name', 'asc')->get();
        $supply_locations = SupplyLocation::where('location', '<>', 'ALMACEN')->orderBy('location', 'asc')->get();
        $resources = Resource::orderBy('name', 'asc')->get();

        return view('prepared_products.edit', compact('prepared_product', 'supplies', 'supply_locations', 'resources'));
    }

    public function update(Request $request, PreparedProduct $prepared_product)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'ingredients' => '',
            'resources' => '',
        ]);

        $existing_prepared_product = $this->update_callback($prepared_product->id, $validated_data['name']);

        if($existing_prepared_product) {
            request()->session()->flash('alertmessage', [
                'message' => 'El preparado ya se encuentra registrado',
                'type' => 'error',
            ]);
    
            return back();
        }

        $prepared_product->name = $validated_data['name'];
        $prepared_product->save();

        // Delete old ingredients
        PreparedProductIngredient::where('prepared_product_id', $prepared_product->id)->delete();

        // Delete old resources
        PreparedProductResource::where('prepared_product_id', $prepared_product->id)->delete();

        // Create new ingredients if exist
        if(isset($validated_data['ingredients'])) {
            for($i = 0; $i < count($validated_data['ingredients']['quantity']); $i++) {
                PreparedProductIngredient::create([
                    'quantity' => $validated_data['ingredients']['quantity'][$i],
                    'quantity_to_produce' => $validated_data['ingredients']['quantity_to_produce'][$i],
                    'prepared_product_id' => $prepared_product->id,
                    'supply_id' => $validated_data['ingredients']['supply_id'][$i],
                    'measurement_unit_id' => $validated_data['ingredients']['measurement_unit_id'][$i],
                    'supply_location_id' => $validated_data['ingredients']['supply_location_id'][$i]
                ]);
            }
        }

        // Create new resources if exist
        if(isset($validated_data['resources'])) {
            for($i = 0; $i < count($validated_data['resources']['quantity_to_produce']); $i++) {
                PreparedProductResource::create([
                    'production_time' => $validated_data['resources']['production_time'][$i],
                    'quantity_to_produce' => $validated_data['resources']['quantity_to_produce'][$i],
                    'prepared_product_id' => $prepared_product->id,
                    'resource_id' => $validated_data['resources']['resource_id'][$i],
                ]);
            }
        }

        request()->session()->flash('alertmessage', [
            'message' => 'Preparado actualizado',
            'type' => 'success',
        ]);

        return redirect('/prepared_products');
    }

    private function store_callback($name)
    {
        // Search prepared product by name
        $prepared_product = PreparedProduct::where('name', $name)->first();
        return $prepared_product;
    }

    private function update_callback($id, $name)
    {
        // Search prepared product by name different from id
        $prepared_product = PreparedProduct::where('id', '<>', $id)->where('name', $name)->first();
        return $prepared_product;
    }

    public function detail(PreparedProduct $prepared_product)
    {
        return view('prepared_products.detail', compact('prepared_product'));
    }
}
