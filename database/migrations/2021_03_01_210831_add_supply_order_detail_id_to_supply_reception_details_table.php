<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplyOrderDetailIdToSupplyReceptionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_reception_details', function (Blueprint $table) {
            $table->integer('supply_order_detail_id')->unsigned()->after('supply_reception_id');

            $table->foreign('supply_order_detail_id')->references('id')->on('supply_order_details');
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
        Schema::table('supply_reception_details', function (Blueprint $table) {
            //
        });
    }
}
