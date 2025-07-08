<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupplyLocation;

class SupplyLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SupplyLocation::insert(
            ['location' => 'ALMACEN', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['location' => 'PLANTA', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['location' => 'HORNEADO', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        );
    }
}
