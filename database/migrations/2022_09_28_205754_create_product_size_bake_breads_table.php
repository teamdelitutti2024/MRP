<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSizeBakeBreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_size_bake_breads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->timestamps();
            $table->integer('product_size_id')->unsigned();
            $table->integer('bake_bread_size_id')->unsigned();

            $table->foreign('product_size_id')->references('id')->on('product_sizes');
            $table->foreign('bake_bread_size_id')->references('id')->on('bake_bread_sizes');
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
        Schema::dropIfExists('product_size_bake_breads');
    }
}
