<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBakeBreadSizeNameToStockLevel2DetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_level_2_details', function (Blueprint $table) {
            $table->string('bake_bread_size_name', 100)->after('bake_bread_name');
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
