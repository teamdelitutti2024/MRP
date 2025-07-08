<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteBakeBreadIdFromStockLevel2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_level_2', function (Blueprint $table) {
            $table->dropForeign(['bake_bread_id']);
            $table->dropColumn(['bake_bread_id', 'bake_bread_name']);
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
        Schema::table('stock_level_2', function (Blueprint $table) {
            //
        });
    }
}
