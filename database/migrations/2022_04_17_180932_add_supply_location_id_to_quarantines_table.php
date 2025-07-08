<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplyLocationIdToQuarantinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quarantines', function (Blueprint $table) {
            $table->integer('supply_location_id')->unsigned();

            $table->foreign('supply_location_id')->references('id')->on('supply_locations');
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
        Schema::table('quarantines', function (Blueprint $table) {
            //
        });
    }
}
