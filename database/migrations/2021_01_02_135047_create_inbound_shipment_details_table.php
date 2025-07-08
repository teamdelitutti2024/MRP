<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInboundShipmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbound_shipment_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name', 100);
            $table->integer('quantity');
            $table->boolean('status');
            $table->text('incomplete_justification')->nullable();
            $table->integer('product_id')->unsigned();
            $table->integer('inbound_shipment_id')->unsigned();
            $table->integer('product_size_id')->unsigned();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('inbound_shipment_id')->references('id')->on('inbound_shipments');
            $table->foreign('product_size_id')->references('id')->on('product_sizes'); 
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
        Schema::dropIfExists('inbound_shipment_details');
    }
}
