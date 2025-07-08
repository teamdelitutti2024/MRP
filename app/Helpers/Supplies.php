<?php

namespace App;

use App\Models\ProductSize;
use App\Models\BakeBreadSize;
use App\Models\PreparedProduct;
use App\Models\PreparedProductIngredient;
use App\Models\PreparedProductResource;
use App\Models\Stock;
use App\Models\StockLevel2;

class SuppliesHelper 
{
    public static function calculate_products_supplies($products) 
    {
        $supplies = array();

        // Iterate each product
        foreach($products as $element) {
            $total_ingredients = array();
            $total_resources = array();

            $product_size = ProductSize::find($element['id']);

            // Get the ingredients for the product size
            foreach($product_size->ingredients as $ingredient) {
                $ingredient->quantity = $ingredient->quantity / $ingredient->quantity_to_produce;
                $total_ingredients[] = $ingredient;
            }

            // Get the resources for the product size
            foreach($product_size->resources as $resource) {
                $resource->production_time = $resource->production_time / $resource->quantity_to_produce;
                $total_resources[] = $resource;
            }

            foreach($product_size->prepared_product_data as $prepared_product_data) {
                // Get the ingredients for each prepared product associated to product size
                $prepared_product_ingredients = PreparedProductIngredient::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();

                foreach($prepared_product_ingredients as $prepared_product_ingredient) {
                    $prepared_product_ingredient->quantity = ($prepared_product_ingredient->quantity / $prepared_product_ingredient->quantity_to_produce)  * $prepared_product_data->quantity;
                    $total_ingredients[] = $prepared_product_ingredient;
                }

                // Get the resources for each prepared product associated to product size
                $prepared_product_resources = PreparedProductResource::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();

                foreach($prepared_product_resources as $resource) {
                    $resource->production_time = ($resource->production_time / $resource->quantity_to_produce) * $prepared_product_data->quantity;
                    $total_resources[] = $resource;
                }
            }

            foreach($product_size->bake_breads as $bake_bread) {
                // Get the ingredients for the bake bread size
                foreach($bake_bread->bake_bread->ingredients as $ingredient) {
                    $ingredient->quantity = ($ingredient->quantity / $ingredient->quantity_to_produce) * $bake_bread->quantity;
                    $total_ingredients[] = $ingredient;
                }

                // Get the resources for the bake bread size
                foreach($bake_bread->bake_bread->resources as $resource) {
                    $resource->production_time = ($resource->production_time / $resource->quantity_to_produce) * $bake_bread->quantity;
                    $total_resources[] = $resource;
                }

                foreach($bake_bread->bake_bread->prepared_product_data as $prepared_product_data) {
                    // Get the ingredients for each prepared product associated to bake bread size
                    $prepared_product_ingredients = PreparedProductIngredient::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();

                    foreach($prepared_product_ingredients as $prepared_product_ingredient) {
                        $prepared_product_ingredient->quantity = (($prepared_product_ingredient->quantity / $prepared_product_ingredient->quantity_to_produce) * $prepared_product_data->quantity) * $bake_bread->quantity;
                        $total_ingredients[] = $prepared_product_ingredient;
                    }

                    // Get the resources for each prepared product associated to bake bread size
                    $prepared_product_resources = PreparedProductResource::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();

                    foreach($prepared_product_resources as $resource) {
                        $resource->production_time = (($resource->production_time / $resource->quantity_to_produce) * $prepared_product_data->quantity) * $bake_bread->quantity;
                        $total_resources[] = $resource;
                    }
                }
            }

            // Iterate each ingredient
            foreach($total_ingredients as $ingredient) {
                $quantity = round($ingredient->quantity * $element['quantity'], 3);

                $cost = $ingredient->supply->average_cost == 0 ? $ingredient->supply->initial_cost : $ingredient->supply->average_cost;

                // Check if ingredient already exists on supplies array
                if(array_key_exists($ingredient->supply->name, $supplies)) {
                    // Update accumulated quantity
                    $supplies[$ingredient->supply->name]['quantity'] = $supplies[$ingredient->supply->name]['quantity'] + $quantity;
                }
                else {
                    // Get available quantity for ingredient
                    $stock = Stock::where('supply_id', $ingredient->supply_id)->where('supply_location_id', 1)->first();

                    // Add ingredient to supplies array
                    $supplies[$ingredient->supply->name] = array(
                        'supply_id' => $ingredient->supply_id,
                        'supply_key' => $ingredient->supply->supply_key,
                        'supply' => $ingredient->supply->name,
                        'quantity' => $quantity,
                        'measurement_unit_id' => $ingredient->supply->measurement_unit_id,
                        'measure' => $ingredient->supply->measurement_unit->measure,
                        'average_cost' => $cost,
                        'available_quantity' => $stock->quantity,
                    );
                }
            }

            // Iterate each resource
            foreach($total_resources as $resource) {
                $production_time = round($resource->production_time * $element['quantity'], 3);

                // Check if resource already exists on supplies array
                if(array_key_exists($resource->resource_id, $supplies)) {
                    // Update accumulated quantity (production time)
                    $supplies[$resource->resource_id]['quantity'] = $supplies[$resource->resource_id]['quantity'] + $production_time;
                }
                else {
                    // Add ingredient to supplies array
                    $supplies[$resource->resource_id] = array(
                        'supply_id' => $resource->resource_id,
                        'supply_key' => $resource->resource->resource_key,
                        'supply' => $resource->resource->name,
                        'quantity' => $production_time,
                        'measurement_unit_id' => '',
                        'measure' => 'Horas',
                        'average_cost' => $resource->resource->cost,
                        'available_quantity' => '',
                    );
                }
            }
        }

        return $supplies;
    }

    public static function calculate_bake_breads_supplies($bake_breads) 
    {
        $supplies = array();

        // Iterate each product
        foreach($bake_breads as $element) {
            $total_ingredients = array();
            $total_resources = array();
            $total_bake_breads = array();

            $bake_bread_size = BakeBreadSize::find($element['id']);

            // Get the ingredients for the bake bread size
            foreach($bake_bread_size->ingredients as $ingredient) {
                $ingredient->quantity = $ingredient->quantity / $ingredient->quantity_to_produce;
                $total_ingredients[] = $ingredient;
            }

            // Get the resources for the bake bread size
            foreach($bake_bread_size->resources as $resource) {
                $resource->production_time = $resource->production_time / $resource->quantity_to_produce;
                $total_resources[] = $resource;
            }

            // Get the related bake bread sizes for the bake bread size
            foreach($bake_bread_size->bake_bread_sizes as $related_bake_bread_size) {
                $total_bake_breads[] = $related_bake_bread_size;
            }

            // Get the prepared data for the bake bread size
            foreach($bake_bread_size->prepared_product_data as $prepared_product_data) {
                // Get the ingredients for each prepared product associated to bake bread size
                $prepared_product_ingredients = PreparedProductIngredient::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();

                foreach($prepared_product_ingredients as $prepared_product_ingredient) {
                    $prepared_product_ingredient->quantity = ($prepared_product_ingredient->quantity / $prepared_product_ingredient->quantity_to_produce)  * $prepared_product_data->quantity;
                    $total_ingredients[] = $prepared_product_ingredient;
                }

                // Get the resources for each prepared product associated to bake bread size
                $prepared_product_resources = PreparedProductResource::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();

                foreach($prepared_product_resources as $resource) {
                    $resource->production_time = ($resource->production_time / $resource->quantity_to_produce) * $prepared_product_data->quantity;
                    $total_resources[] = $resource;
                }
            }

            // Iterate each ingredient
            foreach($total_ingredients as $ingredient) {
                $quantity = round($ingredient->quantity * $element['quantity'], 3);

                $cost = $ingredient->supply->average_cost == 0 ? $ingredient->supply->initial_cost : $ingredient->supply->average_cost;

                // Check if ingredient already exists on supplies array
                if(array_key_exists($ingredient->supply->name, $supplies)) {
                    // Update accumulated quantity
                    $supplies[$ingredient->supply->name]['quantity'] = $supplies[$ingredient->supply->name]['quantity'] + $quantity;
                }
                else {
                    // Get available quantity for ingredient
                    $stock = Stock::where('supply_id', $ingredient->supply_id)->where('supply_location_id', 1)->first();

                    // Add ingredient to supplies array
                    $supplies[$ingredient->supply->name] = array(
                        'supply_id' => $ingredient->supply_id,
                        'supply_key' => $ingredient->supply->supply_key,
                        'supply' => $ingredient->supply->name,
                        'quantity' => $quantity,
                        'measurement_unit_id' => $ingredient->supply->measurement_unit_id,
                        'measure' => $ingredient->supply->measurement_unit->measure,
                        'average_cost' => $cost,
                        'available_quantity' => $stock->quantity,
                    );
                }
            }

            // Iterate each resource
            foreach($total_resources as $res) {
                $production_time = round($res->production_time * $element['quantity'], 3);

                // Check if resource already exists on supplies array
                if(array_key_exists($res->resource->name, $supplies)) {
                    // Update accumulated quantity (production time)
                    $supplies[$res->resource->name]['quantity'] = $supplies[$res->resource->name]['quantity'] + $production_time;
                }
                else {
                    // Add resource to supplies array
                    $supplies[$res->resource->name] = array(
                        'supply_id' => $res->resource_id,
                        'supply_key' => $res->resource->resource_key,
                        'supply' => $res->resource->name,
                        'quantity' => $production_time,
                        'measurement_unit_id' => '',
                        'measure' => 'Horas',
                        'average_cost' => $res->resource->cost,
                        'available_quantity' => '',
                    );
                }
            }

            // Iterate each bake bread
            foreach($total_bake_breads as $bake_bread) {
                $total_ingredients_bake_breads = array();
                $total_resources_bake_breads = array();

                // Get the ingredients for the bake bread size
                foreach($bake_bread->related_bake_bread_size->ingredients as $ingredient) {
                    $ingredient->quantity = $ingredient->quantity / $ingredient->quantity_to_produce;
                    $total_ingredients_bake_breads[] = $ingredient;
                }

                // Get the resources for the bake bread size
                foreach($bake_bread->related_bake_bread_size->resources as $resource) {
                    $resource->production_time = $resource->production_time / $resource->quantity_to_produce;
                    $total_resources_bake_breads[] = $resource;
                }

                // Get the prepared data for the bake bread size
                foreach($bake_bread->related_bake_bread_size->prepared_product_data as $prepared_product_data) {
                    // Get the ingredients for each prepared product associated to bake bread size
                    $prepared_product_ingredients = PreparedProductIngredient::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();
    
                    foreach($prepared_product_ingredients as $prepared_product_ingredient) {
                        $prepared_product_ingredient->quantity = ($prepared_product_ingredient->quantity / $prepared_product_ingredient->quantity_to_produce)  * $prepared_product_data->quantity;
                        $total_ingredients_bake_breads[] = $prepared_product_ingredient;
                    }
    
                    // Get the resources for each prepared product associated to bake bread size
                    $prepared_product_resources = PreparedProductResource::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();
    
                    foreach($prepared_product_resources as $resource) {
                        $resource->production_time = ($resource->production_time / $resource->quantity_to_produce) * $prepared_product_data->quantity;
                        $total_resources_bake_breads[] = $resource;
                    }
                }

                // Iterate each ingredient
                foreach($total_ingredients_bake_breads as $ingredient) {
                    $quantity = round($ingredient->quantity * $bake_bread->quantity * $element['quantity'], 3);
    
                    $cost = $ingredient->supply->average_cost == 0 ? $ingredient->supply->initial_cost : $ingredient->supply->average_cost;
    
                    // Check if ingredient already exists on supplies array
                    if(array_key_exists($ingredient->supply->name, $supplies)) {
                        // Update accumulated quantity
                        $supplies[$ingredient->supply->name]['quantity'] = $supplies[$ingredient->supply->name]['quantity'] + $quantity;
                    }
                    else {
                        // Get available quantity for ingredient
                        $stock = Stock::where('supply_id', $ingredient->supply_id)->where('supply_location_id', 1)->first();
    
                        // Add ingredient to supplies array
                        $supplies[$ingredient->supply->name] = array(
                            'supply_id' => $ingredient->supply_id,
                            'supply_key' => $ingredient->supply->supply_key,
                            'supply' => $ingredient->supply->name,
                            'quantity' => $quantity,
                            'measurement_unit_id' => $ingredient->supply->measurement_unit_id,
                            'measure' => $ingredient->supply->measurement_unit->measure,
                            'average_cost' => $cost,
                            'available_quantity' => $stock->quantity,
                        );
                    }
                }

                // Iterate each resource
                foreach($total_resources_bake_breads as $res) {
                    $production_time = round($res->production_time * $bake_bread->quantity * $element['quantity'], 3);
    
                    // Check if resource already exists on supplies array
                    if(array_key_exists($res->resource->name, $supplies)) {
                        // Update accumulated quantity (production time)
                        $supplies[$res->resource->name]['quantity'] = $supplies[$res->resource->name]['quantity'] + $production_time;
                    }
                    else {
                        // Add resource to supplies array
                        $supplies[$res->resource->name] = array(
                            'supply_id' => $res->resource_id,
                            'supply_key' => $res->resource->resource_key,
                            'supply' => $res->resource->name,
                            'quantity' => $production_time,
                            'measurement_unit_id' => '',
                            'measure' => 'Horas',
                            'average_cost' => $res->resource->cost,
                            'available_quantity' => '',
                        );
                    }
                }
            }
        }

        return $supplies;
    }

    public static function calculate_bake_breads_supplies_for_projection($bake_breads) 
    {
        $supplies = array();
        $bake_breads_data = array();
        $prepared_products_data = array();

        // Iterate each product
        foreach($bake_breads as $element) {
            $total_ingredients = array();
            $total_resources = array();
            $total_bake_breads = array();
            $total_prepared_products = array();

            $bake_bread_size = BakeBreadSize::find($element['id']);

            // Get the ingredients for the bake bread size
            foreach($bake_bread_size->ingredients as $ingredient) {
                $ingredient->quantity = $ingredient->quantity / $ingredient->quantity_to_produce;
                $total_ingredients[] = $ingredient;
            }

            // Get the resources for the bake bread size
            foreach($bake_bread_size->resources as $resource) {
                $resource->production_time = $resource->production_time / $resource->quantity_to_produce;
                $total_resources[] = $resource;
            }

            // Get the related bake bread sizes for the bake bread size
            foreach($bake_bread_size->bake_bread_sizes as $related_bake_bread_size) {
                $total_bake_breads[] = $related_bake_bread_size;
            }

            // Get the prepared data for the bake bread size
            foreach($bake_bread_size->prepared_product_data as $prepared_product_data) {
                $total_prepared_products[] = $prepared_product_data;

                // Get the ingredients for each prepared product associated to bake bread size
                $prepared_product_ingredients = PreparedProductIngredient::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();

                foreach($prepared_product_ingredients as $prepared_product_ingredient) {
                    $prepared_product_ingredient->quantity = ($prepared_product_ingredient->quantity / $prepared_product_ingredient->quantity_to_produce)  * $prepared_product_data->quantity;
                    $total_ingredients[] = $prepared_product_ingredient;
                }

                // Get the resources for each prepared product associated to bake bread size
                $prepared_product_resources = PreparedProductResource::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();

                foreach($prepared_product_resources as $resource) {
                    $resource->production_time = ($resource->production_time / $resource->quantity_to_produce) * $prepared_product_data->quantity;
                    $total_resources[] = $resource;
                }
            }

            // Iterate each ingredient
            foreach($total_ingredients as $ingredient) {
                $quantity = round($ingredient->quantity * $element['quantity'], 3);

                $cost = $ingredient->supply->average_cost == 0 ? $ingredient->supply->initial_cost : $ingredient->supply->average_cost;

                // Check if ingredient already exists on supplies array
                if(array_key_exists($ingredient->supply->name, $supplies)) {
                    // Update accumulated quantity
                    $supplies[$ingredient->supply->name]['quantity'] = $supplies[$ingredient->supply->name]['quantity'] + $quantity;
                }
                else {
                    // Get available quantity for ingredient
                    $stock = Stock::where('supply_id', $ingredient->supply_id)->where('supply_location_id', 1)->first();

                    // Add ingredient to supplies array
                    $supplies[$ingredient->supply->name] = array(
                        'supply_id' => $ingredient->supply_id,
                        'supply_key' => $ingredient->supply->supply_key,
                        'supply' => $ingredient->supply->name,
                        'quantity' => $quantity,
                        'measurement_unit_id' => $ingredient->supply->measurement_unit_id,
                        'measure' => $ingredient->supply->measurement_unit->measure,
                        'average_cost' => $cost,
                        'available_quantity' => $stock->quantity,
                    );
                }
            }

            // Iterate each prepared product data
            foreach($total_prepared_products as $prepared_product_data) {
                $quantity = round($prepared_product_data->quantity * $element['quantity'], 3);

                // Check if prepared product already exists on prepared products data array
                if(array_key_exists($prepared_product_data->prepared_product_id, $prepared_products_data)) {
                    // Update accumulated quantity
                    $prepared_products_data[$prepared_product_data->prepared_product_id]['quantity'] = $prepared_products_data[$prepared_product_data->prepared_product_id]['quantity'] + $quantity;
                }
                else {        
                    // Add prepared product to prepared products data array
                    $prepared_products_data[$prepared_product_data->id] = array(
                        'prepared_product_id' => $prepared_product_data->prepared_product_id,
                        'prepared_product_key' => $prepared_product_data->prepared_product->product_key,
                        'prepared_product' => $prepared_product_data->prepared_product->name,
                        'quantity' => $quantity,
                    );
                }
            }

            // Iterate each resource
            foreach($total_resources as $res) {
                $production_time = round($res->production_time * $element['quantity'], 3);

                // Check if resource already exists on supplies array
                if(array_key_exists($res->resource->name, $supplies)) {
                    // Update accumulated quantity (production time)
                    $supplies[$res->resource->name]['quantity'] = $supplies[$res->resource->name]['quantity'] + $production_time;
                }
                else {
                    // Add resource to supplies array
                    $supplies[$res->resource->name] = array(
                        'supply_id' => $res->resource_id,
                        'supply_key' => $res->resource->resource_key,
                        'supply' => $res->resource->name,
                        'quantity' => $production_time,
                        'measurement_unit_id' => '',
                        'measure' => 'Horas',
                        'average_cost' => $res->resource->cost,
                        'available_quantity' => '',
                    );
                }
            }

            // Iterate each bake bread from total bake breads array
            foreach($total_bake_breads as $bake_bread) {
                $quantity = round($bake_bread->quantity * $element['quantity'], 3);

                // Check if bake bread already exists on bake breads data array
                if(array_key_exists($bake_bread->related_bake_bread_size_id, $bake_breads_data)) {
                    // Update accumulated quantity
                    $bake_breads_data[$bake_bread->related_bake_bread_size_id]['quantity'] = $bake_breads_data[$bake_bread->related_bake_bread_size_id]['quantity'] + $quantity;
                }
                else {
                    // Get available quantity for stock level 2
                    $stock_level_2 = StockLevel2::where('bake_bread_size_id', $bake_bread->related_bake_bread_size_id)->first();
    
                    // Add bake bread to bake breads data array
                    $bake_breads_data[$bake_bread->related_bake_bread_size_id] = array(
                        'bake_bread_size_id' => $bake_bread->related_bake_bread_size_id,
                        'bake_bread_size_key' => $bake_bread->related_bake_bread_size->bake_bread_size_key,
                        'bake_bread_size' => $bake_bread->related_bake_bread_size->name,
                        'quantity' => $quantity,
                        'available_quantity' => $stock_level_2 ? $stock_level_2->quantity : 0,
                    );
                }

                $total_ingredients_bake_breads = array();
                $total_resources_bake_breads = array();
                $total_prepared_products_bake_breads = array();

                // Get the ingredients for the bake bread size
                foreach($bake_bread->related_bake_bread_size->ingredients as $ingredient) {
                    $ingredient->quantity = $ingredient->quantity / $ingredient->quantity_to_produce;
                    $total_ingredients_bake_breads[] = $ingredient;
                }

                // Get the resources for the bake bread size
                foreach($bake_bread->related_bake_bread_size->resources as $resource) {
                    $resource->production_time = $resource->production_time / $resource->quantity_to_produce;
                    $total_resources_bake_breads[] = $resource;
                }

                // Get the prepared data for the bake bread size
                foreach($bake_bread->related_bake_bread_size->prepared_product_data as $prepared_product_data) {
                    $total_prepared_products_bake_breads[] = $prepared_product_data;

                    // Get the ingredients for each prepared product associated to bake bread size
                    $prepared_product_ingredients = PreparedProductIngredient::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();
    
                    foreach($prepared_product_ingredients as $prepared_product_ingredient) {
                        $prepared_product_ingredient->quantity = ($prepared_product_ingredient->quantity / $prepared_product_ingredient->quantity_to_produce)  * $prepared_product_data->quantity;
                        $total_ingredients_bake_breads[] = $prepared_product_ingredient;
                    }
    
                    // Get the resources for each prepared product associated to bake bread size
                    $prepared_product_resources = PreparedProductResource::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();
    
                    foreach($prepared_product_resources as $resource) {
                        $resource->production_time = ($resource->production_time / $resource->quantity_to_produce) * $prepared_product_data->quantity;
                        $total_resources_bake_breads[] = $resource;
                    }
                }

                // Iterate each ingredient
                foreach($total_ingredients_bake_breads as $ingredient) {
                    $quantity = round($ingredient->quantity * $bake_bread->quantity * $element['quantity'], 3);
    
                    $cost = $ingredient->supply->average_cost == 0 ? $ingredient->supply->initial_cost : $ingredient->supply->average_cost;
    
                    // Check if ingredient already exists on supplies array
                    if(array_key_exists($ingredient->supply->name, $supplies)) {
                        // Update accumulated quantity
                        $supplies[$ingredient->supply->name]['quantity'] = $supplies[$ingredient->supply->name]['quantity'] + $quantity;
                    }
                    else {
                        // Get available quantity for ingredient
                        $stock = Stock::where('supply_id', $ingredient->supply_id)->where('supply_location_id', 1)->first();
    
                        // Add ingredient to supplies array
                        $supplies[$ingredient->supply->name] = array(
                            'supply_id' => $ingredient->supply_id,
                            'supply_key' => $ingredient->supply->supply_key,
                            'supply' => $ingredient->supply->name,
                            'quantity' => $quantity,
                            'measurement_unit_id' => $ingredient->supply->measurement_unit_id,
                            'measure' => $ingredient->supply->measurement_unit->measure,
                            'average_cost' => $cost,
                            'available_quantity' => $stock->quantity,
                        );
                    }
                }

                // Iterate each prepared product data
                foreach($total_prepared_products_bake_breads as $prepared_product_data) {
                    $quantity = round($prepared_product_data->quantity * $bake_bread->quantity * $element['quantity'], 3);

                    // Check if prepared product already exists on prepared products data array
                    if(array_key_exists($prepared_product_data->prepared_product_id, $prepared_products_data)) {
                        // Update accumulated quantity
                        $prepared_products_data[$prepared_product_data->prepared_product_id]['quantity'] = $prepared_products_data[$prepared_product_data->prepared_product_id]['quantity'] + $quantity;
                    }
                    else {        
                        // Add prepared product to prepared products data array
                        $prepared_products_data[$prepared_product_data->id] = array(
                            'prepared_product_id' => $prepared_product_data->prepared_product_id,
                            'prepared_product_key' => $prepared_product_data->prepared_product->product_key,
                            'prepared_product' => $prepared_product_data->prepared_product->name,
                            'quantity' => $quantity,
                        );
                    }
                }

                // Iterate each resource
                foreach($total_resources_bake_breads as $res) {
                    $production_time = round($res->production_time * $bake_bread->quantity * $element['quantity'], 3);
    
                    // Check if resource already exists on supplies array
                    if(array_key_exists($res->resource->name, $supplies)) {
                        // Update accumulated quantity (production time)
                        $supplies[$res->resource->name]['quantity'] = $supplies[$res->resource->name]['quantity'] + $production_time;
                    }
                    else {
                        // Add resource to supplies array
                        $supplies[$res->resource->name] = array(
                            'supply_id' => $res->resource_id,
                            'supply_key' => $res->resource->resource_key,
                            'supply' => $res->resource->name,
                            'quantity' => $production_time,
                            'measurement_unit_id' => '',
                            'measure' => 'Horas',
                            'average_cost' => $res->resource->cost,
                            'available_quantity' => '',
                        );
                    }
                }
            }
        }

        return [
            'supplies' => $supplies,
            'bake_breads' => $bake_breads_data,
            'prepared_products' => $prepared_products_data,
        ];
    }

    public static function calculate_prepared_products_supplies($products) 
    {
        $supplies = array();

        // Iterate each product
        foreach($products as $element) {
            $total_ingredients = array();
            $total_resources = array();

            $prepared_product = PreparedProduct::find($element['id']);

            // Get the ingredients for the prepared product
            foreach($prepared_product->ingredients as $ingredient) {
                $ingredient->quantity = $ingredient->quantity / $ingredient->quantity_to_produce;
                $total_ingredients[] = $ingredient;
            }

            // Get the resources for the prepared product
            foreach($prepared_product->resources as $resource) {
                $resource->production_time = $resource->production_time / $resource->quantity_to_produce;
                $total_resources[] = $resource;
            }

            // Iterate each ingredient
            foreach($total_ingredients as $ingredient) {
                $quantity = round($ingredient->quantity * $element['quantity'], 3);

                $cost = $ingredient->supply->average_cost == 0 ? $ingredient->supply->initial_cost : $ingredient->supply->average_cost;

                // Check if ingredient already exists on supplies array
                if(array_key_exists($ingredient->supply->name, $supplies)) {
                    // Update accumulated quantity
                    $supplies[$ingredient->supply->name]['quantity'] = $supplies[$ingredient->supply->name]['quantity'] + $quantity;
                }
                else {
                    // Get available quantity for ingredient
                    $stock = Stock::where('supply_id', $ingredient->supply_id)->where('supply_location_id', 1)->first();

                    // Add ingredient to supplies array
                    $supplies[$ingredient->supply->name] = array(
                        'supply_id' => $ingredient->supply_id,
                        'supply_key' => $ingredient->supply->supply_key,
                        'supply' => $ingredient->supply->name,
                        'quantity' => $quantity,
                        'measurement_unit_id' => $ingredient->supply->measurement_unit_id,
                        'measure' => $ingredient->supply->measurement_unit->measure,
                        'average_cost' => $cost,
                        'available_quantity' => $stock->quantity,
                    );
                }
            }

            // Iterate each resource
            foreach($total_resources as $resource) {
                $production_time = round($resource->production_time * $element['quantity'], 3);

                // Check if resource already exists on supplies array
                if(array_key_exists($resource->resource_id, $supplies)) {
                    // Update accumulated quantity (production time)
                    $supplies[$resource->resource_id]['quantity'] = $supplies[$resource->resource_id]['quantity'] + $production_time;
                }
                else {
                    // Add ingredient to supplies array
                    $supplies[$resource->resource_id] = array(
                        'supply_id' => $resource->resource_id,
                        'supply_key' => $resource->resource->resource_key,
                        'supply' => $resource->resource->name,
                        'quantity' => $production_time,
                        'measurement_unit_id' => '',
                        'measure' => 'Horas',
                        'average_cost' => $resource->resource->cost,
                        'available_quantity' => '',
                    );
                }
            }
        }

        return $supplies;
    }

    public static function calculate_products_production_supplies($data) 
    {
        $supplies = array();

        // Iterate each element on array
        for($i = 0; $i < count($data['quantity']); $i++) {
            $total_ingredients = array();

            $product_size = ProductSize::find($data['id'][$i]);

            foreach($product_size->ingredients as $ingredient) {
                $ingredient->quantity = $ingredient->quantity / $ingredient->quantity_to_produce;
                $total_ingredients[] = $ingredient;
            }

            foreach($product_size->prepared_product_data as $prepared_product_data) {
                $prepared_product_ingredients = PreparedProductIngredient::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();

                foreach($prepared_product_ingredients as $prepared_product_ingredient) {
                    $prepared_product_ingredient->quantity = ($prepared_product_ingredient->quantity / $prepared_product_ingredient->quantity_to_produce)  * $prepared_product_data->quantity;
                    $total_ingredients[] = $prepared_product_ingredient;
                }
            }

            // Iterate each ingredient
            foreach($total_ingredients as $ingredient) {
                $quantity = round($ingredient->quantity * $data['quantity'][$i], 3);

                $cost = $ingredient->supply->average_cost == 0 ? $ingredient->supply->initial_cost : $ingredient->supply->average_cost;

                // Check if ingredient already exists on supplies array
                if(array_key_exists($ingredient->supply->name . '-' . $ingredient->supply_location->location, $supplies)) {
                    // Update accumulated quantity
                    $supplies[$ingredient->supply->name . '-' . $ingredient->supply_location->location]['quantity'] = $supplies[$ingredient->supply->name . '-' . $ingredient->supply_location->location]['quantity'] + $quantity;
                }
                else {
                    // Add ingredient to supplies array
                    $supplies[$ingredient->supply->name . '-' . $ingredient->supply_location->location] = array(
                        'supply_id' => $ingredient->supply_id,
                        'supply_key' => $ingredient->supply->supply_key,
                        'supply' => $ingredient->supply->name,
                        'supply_location_id' => $ingredient->supply_location_id,
                        'supply_location' => $ingredient->supply_location->location,
                        'quantity' => $quantity,
                        'measurement_unit_id' => $ingredient->supply->measurement_unit_id,
                        'measure' => $ingredient->supply->measurement_unit->measure,
                        'average_cost' => $cost,
                    );
                }
            }
        }

        return $supplies;
    }

    public static function calculate_bake_breads_production_supplies($data) 
    {
        $supplies = array();

        // Iterate each element on array
        for($i = 0; $i < count($data['quantity']); $i++) {
            $total_ingredients = array();

            $bake_bread_size = BakeBreadSize::find($data['id'][$i]);

            foreach($bake_bread_size->ingredients as $ingredient) {
                $ingredient->quantity = $ingredient->quantity / $ingredient->quantity_to_produce;
                $total_ingredients[] = $ingredient;
            }

            foreach($bake_bread_size->prepared_product_data as $prepared_product_data) {
                $prepared_product_ingredients = PreparedProductIngredient::where('prepared_product_id', $prepared_product_data->prepared_product_id)->get();

                foreach($prepared_product_ingredients as $prepared_product_ingredient) {
                    $prepared_product_ingredient->quantity = ($prepared_product_ingredient->quantity / $prepared_product_ingredient->quantity_to_produce)  * $prepared_product_data->quantity;
                    $total_ingredients[] = $prepared_product_ingredient;
                }
            }

            // Iterate each ingredient
            foreach($total_ingredients as $ingredient) {
                $quantity = round($ingredient->quantity * $data['quantity'][$i], 3);

                $cost = $ingredient->supply->average_cost == 0 ? $ingredient->supply->initial_cost : $ingredient->supply->average_cost;

                // Check if ingredient already exists on supplies array
                if(array_key_exists($ingredient->supply->name . '-' . $ingredient->supply_location->location, $supplies)) {
                    // Update accumulated quantity
                    $supplies[$ingredient->supply->name . '-' . $ingredient->supply_location->location]['quantity'] = $supplies[$ingredient->supply->name . '-' . $ingredient->supply_location->location]['quantity'] + $quantity;
                }
                else {
                    // Add ingredient to supplies array
                    $supplies[$ingredient->supply->name . '-' . $ingredient->supply_location->location] = array(
                        'supply_id' => $ingredient->supply_id,
                        'supply_key' => $ingredient->supply->supply_key,
                        'supply' => $ingredient->supply->name,
                        'supply_location_id' => $ingredient->supply_location_id,
                        'supply_location' => $ingredient->supply_location->location,
                        'quantity' => $quantity,
                        'measurement_unit_id' => $ingredient->supply->measurement_unit_id,
                        'measure' => $ingredient->supply->measurement_unit->measure,
                        'average_cost' => $cost,
                    );
                }
            }
        }

        return $supplies;
    }

    public static function calculate_prepared_products_production_supplies($prepared_product_id, $prepared_product_quantity) 
    {
        $supplies = array();

        $total_ingredients = array();

        $prepared_product = PreparedProduct::find($prepared_product_id);

        // Get the ingredients for the prepared product
        foreach($prepared_product->ingredients as $ingredient) {
            $ingredient->quantity = $ingredient->quantity / $ingredient->quantity_to_produce;
            $total_ingredients[] = $ingredient;
        }

        // Iterate each ingredient
        foreach($total_ingredients as $ingredient) {
            $quantity = round($ingredient->quantity * $prepared_product_quantity, 3);

            $cost = $ingredient->supply->average_cost == 0 ? $ingredient->supply->initial_cost : $ingredient->supply->average_cost;

            // Check if ingredient already exists on supplies array
            if(array_key_exists($ingredient->supply->name . '-' . $ingredient->supply_location->location, $supplies)) {
                // Update accumulated quantity
                $supplies[$ingredient->supply->name . '-' . $ingredient->supply_location->location]['quantity'] = $supplies[$ingredient->supply->name . '-' . $ingredient->supply_location->location]['quantity'] + $quantity;
            }
            else {
                // Add ingredient to supplies array
                $supplies[$ingredient->supply->name . '-' . $ingredient->supply_location->location] = array(
                    'supply_id' => $ingredient->supply_id,
                    'supply_key' => $ingredient->supply->supply_key,
                    'supply' => $ingredient->supply->name,
                    'supply_location_id' => $ingredient->supply_location_id,
                    'supply_location' => $ingredient->supply_location->location,
                    'quantity' => $quantity,
                    'measurement_unit_id' => $ingredient->supply->measurement_unit_id,
                    'measure' => $ingredient->supply->measurement_unit->measure,
                    'average_cost' => $cost,
                );
            }
        }

        return $supplies;
    }

    public static function get_supplies_from_supply_orders($supply_orders)
    {
        $supplies = array();

        foreach($supply_orders as $supply_order) {
            foreach($supply_order->supply_order_details as $element) {
                // Add supply to supplies array
                $supplies[] = array(
                    'supply_key' => $element->sup->supply_key,
                    'supply' => $element->supply,
                    'measure' => $element->measurement_unit->measure,
                    'quantity' => $element->quantity,
                    'cost' => $element->cost,
                    'delivery_date' => $supply_order->delivery_date,
                );
            }
        }

        return $supplies;
    }
}
