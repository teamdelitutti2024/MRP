<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInboundShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbound_shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('received_date');
            $table->timestamps();
            $table->integer('departure_shipment_id')->unsigned();
            $table->integer('responsible_id')->unsigned();
            $table->integer('branch_id')->unsigned();

            $table->foreign('departure_shipment_id')->references('id')->on('departure_shipments');
            $table->foreign('responsible_id')->references('id')->on('users');
            $table->foreign('branch_id')->references('id')->on('branches');
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
        Schema::dropIfExists('inbound_shipments');
    }
}
