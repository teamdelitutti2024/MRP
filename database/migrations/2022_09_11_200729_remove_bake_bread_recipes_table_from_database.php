<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveBakeBreadRecipesTableFromDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bake_bread_recipes', function (Blueprint $table) {
            Schema::drop('bake_bread_recipes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bake_bread_recipes', function (Blueprint $table) {
            //
        });
    }
}
