<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreparedProductIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prepared_product_ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('quantity', 10, 3);
            $table->timestamps();
            $table->integer('prepared_product_id')->unsigned();
            $table->integer('supply_id')->unsigned();
            $table->integer('measurement_unit_id')->unsigned();
            $table->integer('supply_location_id')->unsigned();

            $table->foreign('prepared_product_id')->references('id')->on('prepared_products');
            $table->foreign('supply_id')->references('id')->on('supplies');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
            $table->foreign('supply_location_id')->references('id')->on('supply_locations');
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
        Schema::dropIfExists('prepared_product_ingredients');
    }
}
