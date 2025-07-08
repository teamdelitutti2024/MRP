<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBakeBreadSizePreparedDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bake_bread_size_prepared_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity')->default(1);
            $table->integer('quantity_to_produce');
            $table->timestamps();
            $table->integer('bake_bread_size_id')->unsigned();
            $table->integer('prepared_product_id')->unsigned();

            $table->foreign('bake_bread_size_id')->references('id')->on('bake_bread_sizes');
            $table->foreign('prepared_product_id')->references('id')->on('prepared_products');
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
        Schema::dropIfExists('bake_bread_size_prepared_data');
    }
}
