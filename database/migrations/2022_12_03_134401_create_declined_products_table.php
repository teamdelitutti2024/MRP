<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeclinedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declined_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->string('product_name', 100);
            $table->string('product_size_name', 100);
            $table->decimal('price', 10, 3);
            $table->text('comments')->nullable();
            $table->integer('product_id')->unsigned();
            $table->integer('product_size_id')->unsigned();
            $table->integer('stock_level_1_id')->unsigned();
            $table->integer('responsible_id')->unsigned();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('product_size_id')->references('id')->on('product_sizes');
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
        Schema::dropIfExists('declined_products');
    }
}
