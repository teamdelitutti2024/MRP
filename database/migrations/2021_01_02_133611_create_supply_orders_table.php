<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->decimal('total', 10, 2);
            $table->date('delivery_date');
            $table->timestamps();
            $table->integer('supplier_id')->unsigned();
            $table->integer('responsible_id')->unsigned();
            $table->integer('commercial_term_id')->unsigned();

            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('responsible_id')->references('id')->on('users');
            $table->foreign('commercial_term_id')->references('id')->on('commercial_terms');
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
        Schema::dropIfExists('supply_orders');
    }
}
