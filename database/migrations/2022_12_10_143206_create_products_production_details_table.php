<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsProductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_production_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name', 100);
            $table->string('product_size_name', 100);
            $table->integer('quantity');
            $table->decimal('price', 10, 3);
            $table->integer('product_id')->unsigned();
            $table->integer('product_size_id')->unsigned();
            $table->integer('products_production_id')->unsigned();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('product_size_id')->references('id')->on('product_sizes');
            $table->foreign('products_production_id')->references('id')->on('products_production');
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
        Schema::dropIfExists('products_production_details');
    }
}
