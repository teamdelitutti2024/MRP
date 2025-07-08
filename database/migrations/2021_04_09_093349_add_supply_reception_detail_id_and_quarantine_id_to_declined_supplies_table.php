<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplyReceptionDetailIdAndQuarantineIdToDeclinedSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declined_supplies', function (Blueprint $table) {
            $table->integer('supply_reception_detail_id')->unsigned();
            $table->integer('quarantine_id')->unsigned()->nullable();

            $table->foreign('supply_reception_detail_id')->references('id')->on('supply_reception_details');
            $table->foreign('quarantine_id')->references('id')->on('quarantines');
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
        Schema::table('declined_supplies', function (Blueprint $table) {
            //
        });
    }
}
