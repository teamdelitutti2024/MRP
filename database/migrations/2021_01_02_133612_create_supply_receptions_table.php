<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplyReceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_receptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 120);
            $table->decimal('total', 10, 2);
            $table->timestamps();
            $table->integer('supply_order_id')->unsigned();
            $table->integer('responsible_id')->unsigned();

            $table->foreign('supply_order_id')->references('id')->on('supply_orders');
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
        Schema::dropIfExists('supply_receptions');
    }
}
