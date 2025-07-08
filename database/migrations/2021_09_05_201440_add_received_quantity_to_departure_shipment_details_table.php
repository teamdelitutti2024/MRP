<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceivedQuantityToDepartureShipmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departure_shipment_details', function (Blueprint $table) {
            $table->integer('received_quantity')->nullable()->default(0)->after('requested_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departure_shipment_details', function (Blueprint $table) {
            //
        });
    }
}
