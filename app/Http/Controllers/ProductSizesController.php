<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Ingredient;
use App\Models\ProductSizeBakeBread;
use App\Models\ProductSizePreparedData;
use App\Models\ProductSizeResource;
use App\Models\Supply;
use App\Models\SupplyLocation;
use App\Models\BakeBreadSize;
use App\Models\PreparedProduct;
use App\Models\Resource;
use App\BakeBreadsHelper;
use App\PreparedProductsHelper;

class ProductSizesController extends Controller
{
    public function add(Product $product)
    {
        $supplies = Supply::orderBy('name', 'asc')->get();
        $supply_locations = SupplyLocation::where('location', '<>', 'ALMACEN')->orderBy('location', 'asc')->get();
        $bake_breads = BakeBreadSize::orderBy('name', 'asc')->get();
        $prepared_products = PreparedProduct::orderBy('name', 'asc')->get();
        $resources = Resource::orderBy('name', 'asc')->get();

        return view('product_sizes.add', compact('supplies', 'supply_locations', 'prepared_products', 'resources', 'bake_breads', 'product'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'product_id' => 'required',
            'name' => 'required',
            'products_minimum_amount' => 'required|integer',
            'sale_price' => 'required|numeric',
            'complexity' => 'required',
            'ingredients' => '',
            'bake_breads' => '',
            'prepared_products' => '',
            'resources' => '',
        ]);

        $product_size = $this->store_callback($validated_data['name'], $validated_data['product_id']);

        if($product_size) {
            request()->session()->flash('alertmessage', [
                'message' => 'El tamaño ' . $product_size->name . ' ya se encuentra registrado para este producto',
                'type' => 'error',
            ]);
    
            return back();
        }

        $validated_data['complexity'] = $validated_data['complexity'] == 0 ? null : $validated_data['complexity'];
        $validated_data['secondary_bake_bread_size_id'] = !empty($validated_data['secondary_bake_bread_size_id']) ? $validated_data['secondary_bake_bread_size_id'] : null;

        $product_size = ProductSize::create([
            'product_id' => $validated_data['product_id'],
            'name' => $validated_data['name'],
            'products_minimum_amount' => $validated_data['products_minimum_amount'],
            'sale_price' => $validated_data['sale_price'],
            'complexity' => $validated_data['complexity'],
        ]);

        // Build new key to be updated
        $product_size_key = 'PT-' . str_pad($product_size->id, 3, '0', STR_PAD_LEFT);

        // Update product size key
        $product_size->product_size_key = $product_size_key;
        $product_size->saveQuietly();

        // Create new ingredients if exist
        if(isset($validated_data['ingredients'])) {
            for($i = 0; $i < count($validated_data['ingredients']['quantity']); $i++) {
                Ingredient::create([
                    'quantity' => $validated_data['ingredients']['quantity'][$i],
                    'quantity_to_produce' => $validated_data['ingredients']['quantity_to_produce'][$i],
                    'supply_id' => $validated_data['ingredients']['supply_id'][$i],
                    'product_size_id' => $product_size->id,
                    'measurement_unit_id' => $validated_data['ingredients']['measurement_unit_id'][$i],
                    'supply_location_id' => $validated_data['ingredients']['supply_location_id'][$i],
                ]);
            }
        }

        // Create new bake breads if exist
        if(isset($validated_data['bake_breads'])) {
            for($i = 0; $i < count($validated_data['bake_breads']['quantity']); $i++) {
                ProductSizeBakeBread::create([
                    'quantity' => $validated_data['bake_breads']['quantity'][$i],
                    'product_size_id' => $product_size->id,
                    'bake_bread_size_id' => $validated_data['bake_breads']['bake_bread_size_id'][$i],
                ]);
            }
        }

        // Create new prepared products if exist
        if(isset($validated_data['prepared_products'])) {
            for($i = 0; $i < count($validated_data['prepared_products']['quantity']); $i++) {
                ProductSizePreparedData::create([
                    'quantity' => $validated_data['prepared_products']['quantity'][$i],
                    'product_size_id' => $product_size->id,
                    'prepared_product_id' => $validated_data['prepared_products']['prepared_product_id'][$i],
                ]);
            }
        }

        // Create new resources if exist
        if(isset($validated_data['resources'])) {
            for($i = 0; $i < count($validated_data['resources']['quantity_to_produce']); $i++) {
                ProductSizeResource::create([
                    'production_time' => $validated_data['resources']['production_time'][$i],
                    'quantity_to_produce' => $validated_data['resources']['quantity_to_produce'][$i],
                    'product_size_id' => $product_size->id,
                    'resource_id' => $validated_data['resources']['resource_id'][$i],
                ]);
            }
        }

        request()->session()->flash('alertmessage', [
            'message' => 'Tamaño de producto agregado',
            'type' => 'success',
        ]);

        return redirect('/products/detail/' . $product_size->product_id);
    }

    public function get_row()
    {
        $product_size = ProductSize::find(request('id'));
        $product_size->product = $product_size->product;
        return response()->json($product_size);
    }

    public function edit(ProductSize $product_size)
    {
        $supplies = Supply::orderBy('name', 'asc')->get();
        $supply_locations = SupplyLocation::where('location', '<>', 'ALMACEN')->orderBy('location', 'asc')->get();
        $bake_breads = BakeBreadSize::orderBy('name', 'asc')->get();
        $prepared_products = PreparedProduct::orderBy('name', 'asc')->get();
        $resources = Resource::orderBy('name', 'asc')->get();

        // Costs arrays
        $bake_breads_costs = [];
        $prepared_products_costs = [];

        // Calculate general total for product size
        $general_total = 0;

        // Total for ingredients
        foreach($product_size->ingredients as $ingredient) {
            $cost = $ingredient->supply->average_cost == 0 ? round($ingredient->supply->initial_cost / $ingredient->supply->standard_pack, 2) : $ingredient->supply->average_cost;
            $general_total += $ingredient->quantity / $ingredient->quantity_to_produce * $cost;
        }

        // Total for bake breads
        $counter_bake = 0;
        $bake_breads_data = $product_size->bake_breads;
        foreach($bake_breads_data as $bake_bread) {
            // Calculate bake bread size cost
            $cost = BakeBreadsHelper::calculate_cost($bake_bread->bake_bread_size_id, $bake_bread->quantity);
            $bake_breads_costs[$counter_bake] = $cost;
            $general_total += $cost;
            $counter_bake++;
        }

        // Total for prepared products
        $counter_prepared = 0;
        $prepared_products_data = $product_size->prepared_product_data;
        foreach($prepared_products_data as $prepared_product) {
            // Calculate prepared product size cost
            $cost = PreparedProductsHelper::calculate_cost($prepared_product->prepared_product_id, $prepared_product->quantity);
            $prepared_products_costs[$counter_prepared] = $cost;
            $general_total += $cost;
            $counter_prepared++;
        }

        // Total for resources
        $resources_data = $product_size->resources;
        foreach($resources_data as $res) {
            $cost = $res->production_time / $res->quantity_to_produce * $res->resource->cost;
            $general_total += $cost;
        }

        $general_total = number_format($general_total, 2, '.', '');

        return view('product_sizes.edit', compact('product_size', 'supplies', 'supply_locations', 'prepared_products', 'bake_breads', 'resources', 'general_total', 'bake_breads_costs', 'prepared_products_costs', 'bake_breads_data', 'prepared_products_data', 'resources_data'));
    }

    public function update(Request $request, ProductSize $product_size)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'products_minimum_amount' => 'required|integer',
            'sale_price' => 'required|numeric',
            'complexity' => 'required',
            'ingredients' => '',
            'bake_breads' => '',
            'prepared_products' => '',
            'resources' => '',
        ]);

        $existing_product_size = $this->update_callback($product_size->id, $validated_data['name'], $product_size->product_id);

        if($existing_product_size) {
            request()->session()->flash('alertmessage', [
                'message' => 'El tamaño ' . $existing_product_size->name . ' ya se encuentra registrado para este producto',
                'type' => 'error',
            ]);
    
            return back();
        }

        $validated_data['complexity'] = $validated_data['complexity'] == 0 ? null : $validated_data['complexity'];

        $product_size->name = $validated_data['name'];
        $product_size->products_minimum_amount = $validated_data['products_minimum_amount'];
        $product_size->sale_price = $validated_data['sale_price'];
        $product_size->complexity = $validated_data['complexity'];
        $product_size->save();

        // Delete old ingredients
        Ingredient::where('product_size_id', $product_size->id)->delete();

        // Delete old bake breads
        ProductSizeBakeBread::where('product_size_id', $product_size->id)->delete();

        // Delete old prepared products
        ProductSizePreparedData::where('product_size_id', $product_size->id)->delete();

        // Delete old resources
        ProductSizeResource::where('product_size_id', $product_size->id)->delete();

        // Create new ingredients if exist
        if(isset($validated_data['ingredients'])) {
            for($i = 0; $i < count($validated_data['ingredients']['quantity']); $i++) {
                Ingredient::create([
                    'quantity' => $validated_data['ingredients']['quantity'][$i],
                    'quantity_to_produce' => $validated_data['ingredients']['quantity_to_produce'][$i],
                    'supply_id' => $validated_data['ingredients']['supply_id'][$i],
                    'product_size_id' => $product_size->id,
                    'measurement_unit_id' => $validated_data['ingredients']['measurement_unit_id'][$i],
                    'supply_location_id' => $validated_data['ingredients']['supply_location_id'][$i],
                ]);
            }
        }

        // Create new bake breads if exist
        if(isset($validated_data['bake_breads'])) {
            for($i = 0; $i < count($validated_data['bake_breads']['quantity']); $i++) {
                ProductSizeBakeBread::create([
                    'quantity' => $validated_data['bake_breads']['quantity'][$i],
                    'product_size_id' => $product_size->id,
                    'bake_bread_size_id' => $validated_data['bake_breads']['bake_bread_size_id'][$i],
                ]);
            }
        }

        // Create new prepared products if exist
        if(isset($validated_data['prepared_products'])) {
            for($i = 0; $i < count($validated_data['prepared_products']['quantity']); $i++) {
                ProductSizePreparedData::create([
                    'quantity' => $validated_data['prepared_products']['quantity'][$i],
                    'product_size_id' => $product_size->id,
                    'prepared_product_id' => $validated_data['prepared_products']['prepared_product_id'][$i],
                ]);
            }
        }

        // Create new resources if exist
        if(isset($validated_data['resources'])) {
            for($i = 0; $i < count($validated_data['resources']['quantity_to_produce']); $i++) {
                ProductSizeResource::create([
                    'production_time' => $validated_data['resources']['production_time'][$i],
                    'quantity_to_produce' => $validated_data['resources']['quantity_to_produce'][$i],
                    'product_size_id' => $product_size->id,
                    'resource_id' => $validated_data['resources']['resource_id'][$i],
                ]);
            }
        }

        request()->session()->flash('alertmessage', [
            'message' => 'Tamaño de producto actualizado',
            'type' => 'success',
        ]);

        return back();
    }

    private function store_callback($name, $product_id)
    {
        // Search product size by name or key and product id
        $product_size = ProductSize::where('product_id', $product_id)->where('name', $name)->first();
        return $product_size;
    }

    private function update_callback($id, $name, $product_id)
    {
        // Search product size by name or key and product id from different product size id
        $product_size = ProductSize::where('id', '<>', $id)->where('product_id', $product_id)->where('name', $name)->first();
        return $product_size;
    }

    public function get_product_sizes()
    {
        $product_sizes = ProductSize::where('product_id', request('id'))->orderBy('name')->get();
        return response()->json($product_sizes);
    }
}
