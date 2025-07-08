<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreaseDecimalsFromCycleCountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cycle_count_details', function (Blueprint $table) {
            $table->decimal('system_quantity', 10, 3)->change();
            $table->decimal('human_quantity', 10, 3)->change(); 
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
