<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBakeBreadsProductionLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bake_breads_production_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supply_id')->unsigned();
            $table->string('supply_key');
            $table->string('supply');
            $table->integer('supply_location_id')->unsigned();
            $table->string('supply_location');
            $table->decimal('quantity', 10, 3);
            $table->integer('measurement_unit_id')->unsigned();
            $table->string('measure');
            $table->decimal('average_cost', 10, 2);
            $table->integer('production_id')->unsigned();
            $table->timestamps();

            $table->foreign('supply_id')->references('id')->on('supplies');
            $table->foreign('supply_location_id')->references('id')->on('supply_locations');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
            $table->foreign('production_id')->references('id')->on('bake_breads_production');
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
        Schema::dropIfExists('bake_breads_production_log');
    }
}
