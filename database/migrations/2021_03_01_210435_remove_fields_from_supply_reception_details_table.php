<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldsFromSupplyReceptionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_reception_details', function (Blueprint $table) {
            $table->dropColumn('supply');
            $table->dropForeign(['supply_id']);
            $table->dropForeign(['measurement_unit_id']);
            $table->dropColumn('supply_id');
            $table->dropColumn('measurement_unit_id');
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
        Schema::table('supply_reception_details', function (Blueprint $table) {
            //
        });
    }
}
