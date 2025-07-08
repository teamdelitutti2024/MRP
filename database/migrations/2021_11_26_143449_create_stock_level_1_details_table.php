<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockLevel1DetailsTable extends Migration
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
            $table->string('product_name', 100);
            $table->integer('quantity');
            $table->boolean('status');
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->integer('product_id')->unsigned();
            $table->integer('product_size_id')->unsigned();
            $table->integer('stock_level_1_id')->unsigned();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('product_size_id')->references('id')->on('product_sizes');
            $table->foreign('stock_level_1_id')->references('id')->on('stock_level_1');
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
