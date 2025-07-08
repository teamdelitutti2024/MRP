<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplyAndSupplyIdToDeclinedSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declined_supplies', function (Blueprint $table) {
            $table->string('supply')->nullable()->after('id');
            $table->integer('supply_id')->unsigned()->nullable()->after('updated_at');

            $table->foreign('supply_id')->references('id')->on('supplies');
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
        Schema::table('declined_supplies', function (Blueprint $table) {
            //
        });
    }
}
