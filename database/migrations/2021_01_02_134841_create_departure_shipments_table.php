<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartureShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departure_shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('shipment_date');
            $table->timestamps();
            $table->integer('order_id')->unsigned();
            $table->integer('responsible_id')->unsigned();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('responsible_id')->references('id')->on('users');
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
        Schema::dropIfExists('departure_shipments');
    }
}
