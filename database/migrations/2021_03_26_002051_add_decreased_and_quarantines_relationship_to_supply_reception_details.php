<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDecreasedAndQuarantinesRelationshipToSupplyReceptionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_reception_details', function (Blueprint $table) {
            $table->integer('quarantine_id')->unsigned()->nullable()->after('quantity');
            $table->integer('declined_supply_id')->unsigned()->nullable()->after('quantity');

            $table->foreign('declined_supply_id')->references('id')->on('declined_supplies');
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
        Schema::table('supply_reception_details', function (Blueprint $table) {
            //
        });
    }
}
