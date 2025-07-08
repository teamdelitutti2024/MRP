<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockLevel2DetailsTableV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_level_2_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->date('valid_until')->nullable();
            $table->timestamps();
            $table->integer('stock_level_2_id')->unsigned();
            $table->integer('responsible_id')->unsigned();

            $table->foreign('stock_level_2_id')->references('id')->on('stock_level_2');
            $table->foreign('responsible_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_level_2_details');
    }
}
