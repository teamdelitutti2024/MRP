<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeQuantitiesFromStockLevel2DetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_level_2_details', function (Blueprint $table) {
            $table->decimal('quantity', 10, 3)->change();
            $table->decimal('last_quantity', 10, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_level_2_details', function (Blueprint $table) {
            //
        });
    }
}
