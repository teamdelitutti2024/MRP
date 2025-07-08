<?php

namespace App;

use App\SuppliesHelper;

class BakeBreadsHelper 
{
    public static function calculate_cost($bake_bread_size_id, $quantity = 1) 
    {
        $cost = 0;

        $products = array(
            array(
                'id' => $bake_bread_size_id,
                'quantity' => $quantity,
            ),
        );

        // Get bake bread supplies
        $supplies = SuppliesHelper::calculate_bake_breads_supplies($products);

        // Calculate bake bread size cost
        foreach($supplies as $supply) {
            $cost += $supply['average_cost'] * $supply['quantity'];
        }

        return $cost;
    }
}