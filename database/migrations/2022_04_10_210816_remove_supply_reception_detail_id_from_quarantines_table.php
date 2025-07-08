<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSupplyReceptionDetailIdFromQuarantinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quarantines', function (Blueprint $table) {
            $table->dropForeign(['supply_reception_detail_id']);
            $table->dropColumn('supply_reception_detail_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quarantines', function (Blueprint $table) {
            //
        });
    }
}
