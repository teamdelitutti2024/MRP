<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteDeclinedSupplyIdAndQuarantineIdFromSupplyReceptionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_reception_details', function (Blueprint $table) {
            $table->dropForeign(['declined_supply_id']);
            $table->dropForeign(['quarantine_id']);
            $table->dropColumn('declined_supply_id');
            $table->dropColumn('quarantine_id');
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
