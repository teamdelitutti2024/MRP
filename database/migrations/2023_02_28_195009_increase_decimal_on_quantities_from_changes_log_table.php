<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreaseDecimalOnQuantitiesFromChangesLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('changes_log', function (Blueprint $table) {
            $table->decimal('previous_quantity', 15, 3)->change();
            $table->decimal('new_quantity', 15, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('changes_log', function (Blueprint $table) {
            //
        });
    }
}
