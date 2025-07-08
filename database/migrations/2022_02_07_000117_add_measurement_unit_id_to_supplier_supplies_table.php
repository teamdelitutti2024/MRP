<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMeasurementUnitIdToSupplierSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_supplies', function (Blueprint $table) {
            $table->integer('measurement_unit_id')->unsigned()->nullable()->after('supply_id');

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
        Schema::table('supplier_supplies', function (Blueprint $table) {
            //
        });
    }
}
