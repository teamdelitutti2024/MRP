<?php

namespace App;

use App\Models\ProductSize;
use App\Models\BakeBreadSize;
use App\Models\PreparedProduct;

class ProductsHelper 
{
    public static function get_products_list($products) 
    {
        $products_list = array();

        foreach($products as $element) {
            $product_size = ProductSize::find($element['id']);

            $products_list[] = array(
                'product' => $product_size->product_size_key . ' ' . $product_size->name,
                'price' => $product_size->price,
                'quantity' => $element['quantity'],
            );
        }

        return $products_list;
    }

    public static function get_bake_breads_list($bake_breads) 
    {
        $bake_breads_list = array();

        foreach($bake_breads as $element) {
            $bake_bread_size = BakeBreadSize::find($element['id']);

            $bake_breads_list[] = array(
                'product' => $bake_bread_size->bake_bread_size_key . ' ' . $bake_bread_size->name,
                'price' => 0,
                'quantity' => $element['quantity'],
            );
        }

        return $bake_breads_list;
    }

    public static function get_prepared_products_list($prepared_products) 
    {
        $prepared_products_list = array();

        foreach($prepared_products as $element) {
            $prepared_product = PreparedProduct::find($element['id']);

            $prepared_products_list[] = array(
                'product' => $prepared_product->product_key . ' ' . $prepared_product->name,
                'price' => 0,
                'quantity' => $element['quantity'],
            );
        }

        return $prepared_products_list;
    }

    public static function get_products_list_from_production($products) 
    {
        $products_list = array();

        for($i = 0; $i < count($products['quantity']); $i++) {
            $product_size = ProductSize::find($products['id'][$i]);

            $products_list[] = array(
                'product' => $product_size->product_size_key . ' ' . $product_size->name,
                'price' => $product_size->price,
                'quantity' => $products['quantity'][$i],
            );
        }

        return $products_list;
    }

    public static function get_bake_breads_list_from_production($bake_breads) 
    {
        $bake_breads_list = array();

        for($i = 0; $i < count($bake_breads['quantity']); $i++) {
            $bake_bread_size = BakeBreadSize::find($bake_breads['id'][$i]);

            $bake_breads_list[] = array(
                'product' => $bake_bread_size->bake_bread_size_key . ' ' . $bake_bread_size->name,
                'price' => 0,
                'quantity' => $bake_breads['quantity'][$i],
            );
        }

        return $bake_breads_list;
    }
}