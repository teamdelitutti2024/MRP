<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplyReceptionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_reception_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('supply', 125);
            $table->decimal('quantity', 10, 2);
            $table->boolean('complete');
            $table->string('status');
            $table->integer('supply_id')->unsigned();
            $table->integer('measurement_unit_id')->unsigned();
            $table->integer('supply_reception_id')->unsigned();

            $table->foreign('supply_id')->references('id')->on('supplies');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
            $table->foreign('supply_reception_id')->references('id')->on('supply_receptions');
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
        Schema::dropIfExists('supply_reception_details');
    }
}
