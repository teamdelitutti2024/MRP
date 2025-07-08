<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeclinedBakeBreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declined_bake_breads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->string('bake_bread_size_name', 100);
            $table->text('comments')->nullable();
            $table->integer('bake_bread_size_id')->unsigned();
            $table->integer('stock_level_2_id')->unsigned();
            $table->integer('responsible_id')->unsigned();
            $table->timestamps();

            $table->foreign('bake_bread_size_id')->references('id')->on('bake_bread_sizes');
            $table->foreign('stock_level_2_id')->references('id')->on('stock_level_2');
            $table->foreign('responsible_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('declined_bake_breads');
    }
}
