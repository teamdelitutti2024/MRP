<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supply;
use App\Models\SupplyLocation;
use App\Models\Stock;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplies = Supply::all();
        $supply_locations = SupplyLocation::all();

        $stock_data = array();

        foreach($supplies as $supply) {
            foreach($supply_locations as $supply_location) {
                $stock_data[] = array(
                    'supply_id' => $supply->id,
                    'supply_location_id' => $supply_location->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
            }
        }

        Stock::insert($stock_data);
    }
}
