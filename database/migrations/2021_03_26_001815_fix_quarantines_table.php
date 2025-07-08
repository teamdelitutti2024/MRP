<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixQuarantinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quarantines', function (Blueprint $table) {
            $table->dropForeign(['supply_reception_id']);
            $table->dropForeign(['supply_id']);
            $table->dropColumn('supply_reception_id');
            $table->dropColumn('supply_id');
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
        Schema::table('quarantines', function (Blueprint $table) {
            //
        });
    }
}
