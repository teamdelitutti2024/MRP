<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameIncompleteJustificationColumnFromInboundShipmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inbound_shipment_details', function (Blueprint $table) {
            $table->renameColumn('incomplete_justification', 'justification');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inbound_shipment_details', function (Blueprint $table) {
            //
        });
    }
}
