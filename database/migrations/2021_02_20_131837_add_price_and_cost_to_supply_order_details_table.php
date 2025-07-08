<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceAndCostToSupplyOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_order_details', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('quantity');
            $table->decimal('cost', 10, 2)->nullable()->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supply_order_details', function (Blueprint $table) {
            //
        });
    }
}
