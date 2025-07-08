<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('quantity', 10, 2);
            $table->timestamps();
            $table->integer('supply_id')->unsigned();
            $table->integer('product_size_id')->unsigned();
            $table->integer('measurement_unit_id')->unsigned();

            $table->foreign('supply_id')->references('id')->on('supplies');
            $table->foreign('product_size_id')->references('id')->on('product_sizes');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
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
        Schema::dropIfExists('ingredients');
    }
}
