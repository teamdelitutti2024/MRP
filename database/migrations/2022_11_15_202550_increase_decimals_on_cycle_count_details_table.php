<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreaseDecimalsOnCycleCountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cycle_count_details', function (Blueprint $table) {
            $table->decimal('stock_quantity', 15, 3)->nullable()->change();
            $table->decimal('counted_quantity', 15, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cycle_count_details', function (Blueprint $table) {
            //
        });
    }
}
