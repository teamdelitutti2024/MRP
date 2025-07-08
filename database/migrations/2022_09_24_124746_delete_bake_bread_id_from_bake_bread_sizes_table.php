<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteBakeBreadIdFromBakeBreadSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bake_bread_sizes', function (Blueprint $table) {
            $table->dropForeign(['bake_bread_id']);
            $table->dropColumn('bake_bread_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bake_bread_sizes', function (Blueprint $table) {
            //
        });
    }
}
