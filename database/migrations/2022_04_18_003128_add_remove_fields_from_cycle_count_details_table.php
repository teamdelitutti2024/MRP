<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemoveFieldsFromCycleCountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cycle_count_details', function (Blueprint $table) {
            $table->string('supply')->after('id');
            $table->integer('supply_location_id')->unsigned()->after('adjustment_responsible_id');
            $table->foreign('supply_location_id')->references('id')->on('supply_locations');
            $table->dropForeign(['supply_reception_detail_id']);
            $table->dropColumn(['supply_reception_detail_id', 'declined_quantity', 'quarantined_quantity']);
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
        Schema::table('cycle_count_details', function (Blueprint $table) {
            //
        });
    }
}
