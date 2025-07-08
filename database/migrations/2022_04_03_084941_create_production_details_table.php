<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('supply');
            $table->string('measure');
            $table->decimal('quantity_to_be_discounted', 10, 3);
            $table->decimal('discounted_quantity', 10, 3);
            $table->boolean('satisfied');
            $table->timestamps();
            $table->integer('supply_id')->unsigned();
            $table->integer('measurement_unit_id')->unsigned();
            $table->integer('production_id')->unsigned();

            $table->foreign('supply_id')->references('id')->on('supplies');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
            $table->foreign('production_id')->references('id')->on('production');
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
        Schema::dropIfExists('production_details');
    }
}
