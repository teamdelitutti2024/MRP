<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartureShipmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departure_shipment_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name', 100);
            $table->integer('quantity');
            $table->boolean('status');
            $table->text('incomplete_justification')->nullable();
            $table->integer('product_id')->unsigned();
            $table->integer('departure_shipment_id')->unsigned();
            $table->integer('product_size_id')->unsigned();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('departure_shipment_id')->references('id')->on('departure_shipments');
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
        Schema::dropIfExists('departure_shipment_details');
    }
}
