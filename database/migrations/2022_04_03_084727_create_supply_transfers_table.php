<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplyTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('supply');
            $table->string('measure');
            $table->decimal('transferred_quantity', 10, 3);
            $table->decimal('remaining_quantity', 10, 3);
            $table->boolean('is_return');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->integer('supply_id')->unsigned();
            $table->integer('measurement_unit_id')->unsigned();
            $table->integer('source_location_id')->unsigned();
            $table->integer('destination_location_id')->unsigned();
            $table->integer('responsible_id')->unsigned();

            $table->foreign('supply_id')->references('id')->on('supplies');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
            $table->foreign('source_location_id')->references('id')->on('supply_locations');
            $table->foreign('destination_location_id')->references('id')->on('supply_locations');
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
        Schema::dropIfExists('supply_transfers');
    }
}
