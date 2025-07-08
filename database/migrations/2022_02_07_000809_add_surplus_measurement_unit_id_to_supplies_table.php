<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSurplusMeasurementUnitIdToSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplies', function (Blueprint $table) {
            $table->integer('surplus_measurement_unit_id')->unsigned()->nullable()->after('surplus_amount');

            $table->foreign('surplus_measurement_unit_id')->references('id')->on('measurement_units');
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
        Schema::table('supplies', function (Blueprint $table) {
            //
        });
    }
}
