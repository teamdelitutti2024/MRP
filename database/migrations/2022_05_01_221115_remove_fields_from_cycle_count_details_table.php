<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldsFromCycleCountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cycle_count_details', function (Blueprint $table) {
            $table->renameColumn('adjustment_justification', 'comments');
            $table->renameColumn('system_available_quantity', 'stock_quantity');
            $table->renameColumn('human_available_quantity', 'counted_quantity');
            $table->dropForeign(['adjustment_responsible_id']);
            $table->dropColumn(['adjustment_responsible_id', 'adjustment_status', 'adjustment_date']);
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
