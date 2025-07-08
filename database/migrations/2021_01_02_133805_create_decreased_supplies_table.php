<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecreasedSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decreased_supplies', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('lost_quantity', 10, 2);
            $table->text('reason');
            $table->string('status');
            $table->dateTime('disabled_date')->nullable();
            $table->timestamps();
            $table->integer('supply_reception_id')->unsigned();
            $table->integer('supply_id')->unsigned();
            $table->integer('measurement_unit_id')->unsigned();
            $table->integer('enabled_responsible_id')->unsigned();
            $table->integer('disabled_responsible_id')->unsigned()->nullable();

            $table->foreign('supply_reception_id')->references('id')->on('supply_receptions');
            $table->foreign('supply_id')->references('id')->on('supplies');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
            $table->foreign('enabled_responsible_id')->references('id')->on('users');
            $table->foreign('disabled_responsible_id')->references('id')->on('users');
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
        Schema::dropIfExists('decreased_supplies');
    }
}
