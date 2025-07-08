<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsProductionBbLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_production_bb_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->integer('bake_bread_size_id')->unsigned();
            $table->integer('production_id')->unsigned();
            $table->timestamps();

            $table->foreign('bake_bread_size_id')->references('id')->on('bake_bread_sizes');
            $table->foreign('production_id')->references('id')->on('products_production');
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
        Schema::dropIfExists('products_production_bb_log');
    }
}
