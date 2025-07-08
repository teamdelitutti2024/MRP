<?php

namespace App;

use App\Models\BakeBreadProduction;
use App\Models\Stock;
use App\SuppliesHelper;
use App\Models\BakeBreadProductionLog;
use App\Models\ProductProductionLog;

class ProductionHelper 
{
    public static function process_production($type, $data, $production_id) 
    {
        file_put_contents("production.txt", "********** Starting production process **********\n\n" . "Date: " . date('Y-m-d') . "\n\n", FILE_APPEND);

        if($type == 1) {
            $supplies = SuppliesHelper::calculate_products_production_supplies($data);
        }
        else {
            $supplies = SuppliesHelper::calculate_bake_breads_production_supplies($data);
        }

        file_put_contents("production.txt", "Supplies required:\n\n", FILE_APPEND);

        $production_log = array();

        // Iterate each supply to be discounted
        foreach($supplies as $supply) {
            file_put_contents("production.txt", "Supply ID: " . $supply['supply_id'] . "\n" . "Supply: " . $supply['supply'] . "\n" . "Quantity to be discounted: " . $supply['quantity'] . "\n" . "Measurement unit ID: " . $supply['measurement_unit_id'] . "\n" . "Measurement unit: " . $supply['measure'] . "\n" . "Supply location: " . $supply['supply_location'] . "\n\n", FILE_APPEND);

            // Get related stock
            $supply_stock = Stock::where('supply_id', $supply['supply_id'])->where('supply_location_id', $supply['supply_location_id'])->first();

            $supply_stock->quantity = $supply_stock->quantity - $supply['quantity'];
            $supply_stock->save();

            // Save current element in production log array
            $production_log[] = array(
                'supply_id' => $supply['supply_id'],
                'supply_key' => $supply['supply_key'],
                'supply' => $supply['supply'],
                'supply_location_id' => $supply['supply_location_id'],
                'supply_location' => $supply['supply_location'],
                'quantity' => $supply['quantity'],
                'measurement_unit_id' => $supply['measurement_unit_id'],
                'measure' => $supply['measure'],
                'average_cost' => $supply['average_cost'],
                'production_id' => $production_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
        }

        // Insert production log
        if($type == 1) {
            ProductProductionLog::insert($production_log);
        }
        else {
            BakeBreadProductionLog::insert($production_log);
        }
    }
}
