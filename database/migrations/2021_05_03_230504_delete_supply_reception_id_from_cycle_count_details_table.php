<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteSupplyReceptionIdFromCycleCountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cycle_count_details', function (Blueprint $table) {
            $table->dropForeign(['supply_reception_id']);
            $table->dropColumn('supply_reception_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cycle_count_details', function (Blueprint $table) {
            //
        });
    }
}
