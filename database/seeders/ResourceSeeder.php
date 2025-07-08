<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Resource::insert(
            ['name' => 'HUMANO', 'resource_key' => 'RC-001', 'cost' => 0, 'description' => 'Recurso humano para contabilizar horas/hombre', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        );
    }
}
