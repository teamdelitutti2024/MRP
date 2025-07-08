<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToBakeBreadsProductionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bake_breads_production', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('responsible_id');
            $table->integer('reversed_responsible_id')->unsigned()->nullable()->after('responsible_id');

            $table->foreign('reversed_responsible_id')->references('id')->on('users');
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
        Schema::table('bake_breads_production', function (Blueprint $table) {
            //
        });
    }
}
