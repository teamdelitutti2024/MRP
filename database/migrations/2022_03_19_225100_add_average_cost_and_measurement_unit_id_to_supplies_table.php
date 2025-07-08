<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAverageCostAndMeasurementUnitIdToSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplies', function (Blueprint $table) {
            $table->decimal('average_cost', 10, 2)->default(0.00)->after('unit');
            $table->integer('measurement_unit_id')->unsigned()->nullable()->after('unit');

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
        Schema::table('supplies', function (Blueprint $table) {
            //
        });
    }
}
