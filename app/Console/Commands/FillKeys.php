<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Supply;
use App\Models\Supplier;
use App\Models\ProductSize;
use App\Models\BakeBreadSize;
use App\Models\PreparedProduct;

class FillKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command fills the required keys for supplies, suppliers, product sizes, bake bread sizes and prepared products';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $supplies = Supply::all();
        $suppliers = Supplier::all(); // PR
        $product_sizes = ProductSize::all(); // PT
        $bake_bread_sizes = BakeBreadSize::all(); // BA
        $prepared_products = PreparedProduct::all(); // ST

        foreach($supplies as $supply) {
            $supply->supply_key = 'MP-' . str_pad($supply->id, 3, '0', STR_PAD_LEFT);
            $supply->saveQuietly();
        }

        foreach($suppliers as $supplier) {
            $supplier->supplier_key = 'PR-' . str_pad($supplier->id, 3, '0', STR_PAD_LEFT);
            $supplier->saveQuietly();
        }

        foreach($product_sizes as $product_size) {
            $product_size->product_size_key = 'PT-' . str_pad($product_size->id, 3, '0', STR_PAD_LEFT);
            $product_size->saveQuietly();
        }

        foreach($bake_bread_sizes as $bake_bread_size) {
            $bake_bread_size->bake_bread_size_key = 'BA-' . str_pad($bake_bread_size->id, 3, '0', STR_PAD_LEFT);
            $bake_bread_size->saveQuietly();
        }

        foreach($prepared_products as $prepared_product) {
            $prepared_product->product_key = 'ST-' . str_pad($prepared_product->id, 3, '0', STR_PAD_LEFT);
            $prepared_product->saveQuietly();
        }

        echo "The keys for supplies, suppliers, product sizes, bake bread sizes and prepared products were filled successfully" . PHP_EOL;
    }
}
