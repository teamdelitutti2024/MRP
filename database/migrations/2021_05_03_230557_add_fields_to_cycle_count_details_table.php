<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCycleCountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cycle_count_details', function (Blueprint $table) {
            $table->dropForeign(['responsible_id']);
            $table->dropColumn('responsible_id');

            $table->integer('adjustment_responsible_id')->unsigned()->nullable();;
            $table->foreign('adjustment_responsible_id')->references('id')->on('users');

            $table->decimal('human_quantity', 10, 3)->nullable()->change();
            $table->decimal('declined_quantity', 10, 3)->after('human_quantity');
            $table->decimal('quarantined_quantity', 10, 3)->after('human_quantity');

            $table->integer('supply_reception_detail_id')->unsigned();
            $table->foreign('supply_reception_detail_id')->references('id')->on('supply_reception_details');
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
        Schema::table('cycle_count_details', function (Blueprint $table) {
            //
        });
    }
}
