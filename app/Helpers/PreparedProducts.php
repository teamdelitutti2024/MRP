<?php

namespace App;

use App\SuppliesHelper;

class PreparedProductsHelper 
{
    public static function calculate_cost($prepared_product_id, $quantity = 1) 
    {
        $cost = 0;

        $products = array(
            array(
                'id' => $prepared_product_id,
                'quantity' => $quantity,
            ),
        );

        // Get prepared product supplies
        $supplies = SuppliesHelper::calculate_prepared_products_supplies($products);

        // Calculate prepared product cost
        foreach($supplies as $supply) {
            $cost += $supply['average_cost'] * $supply['quantity'];
        }

        return $cost;
    }
}