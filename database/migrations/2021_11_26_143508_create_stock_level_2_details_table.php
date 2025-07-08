<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockLevel2DetailsTable extends Migration
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
            $table->string('bake_bread_name', 100);
            $table->integer('quantity');
            $table->boolean('status');
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->integer('bake_bread_id')->unsigned();
            $table->integer('bake_bread_size_id')->unsigned();
            $table->integer('stock_level_2_id')->unsigned();

            $table->foreign('bake_bread_id')->references('id')->on('bake_breads');
            $table->foreign('bake_bread_size_id')->references('id')->on('bake_bread_sizes');
            $table->foreign('stock_level_2_id')->references('id')->on('stock_level_2');
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
        Schema::dropIfExists('stock_level_2_details');
    }
}
