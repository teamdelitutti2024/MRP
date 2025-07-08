<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsFromStockLevel2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_level_2', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('bake_bread_name', 100);
            $table->string('bake_bread_size_name', 100);
            $table->integer('quantity');
            $table->integer('remaining_quantity');
            $table->text('comments')->nullable();
            $table->integer('bake_bread_id')->unsigned();
            $table->integer('bake_bread_size_id')->unsigned();

            $table->foreign('bake_bread_id')->references('id')->on('bake_breads');
            $table->foreign('bake_bread_size_id')->references('id')->on('bake_bread_sizes');
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
