<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsFromDeclinedBakeBreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declined_bake_breads', function (Blueprint $table) {
            $table->dropForeign(['stock_level_2_id']);
            $table->dropColumn('stock_level_2_id');
            $table->date('bake_bread_date')->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('declined_bake_breads', function (Blueprint $table) {
            //
        });
    }
}
