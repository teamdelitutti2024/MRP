<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockLevel1DetailsTableV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_level_1_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->date('valid_until')->nullable();
            $table->timestamps();
            $table->integer('stock_level_1_id')->unsigned();
            $table->integer('responsible_id')->unsigned();

            $table->foreign('stock_level_1_id')->references('id')->on('stock_level_1');
            $table->foreign('responsible_id')->references('id')->on('users');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('stock_level_1_details');
    }
}
