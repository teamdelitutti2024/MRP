<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBakeBreadSizeBakeBreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bake_bread_size_bake_breads', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('quantity', 10, 3);
            $table->timestamps();
            $table->integer('bake_bread_size_id')->unsigned();
            $table->integer('related_bake_bread_size_id')->unsigned();

            $table->foreign('bake_bread_size_id')->references('id')->on('bake_bread_sizes');
            $table->foreign('related_bake_bread_size_id')->references('id')->on('bake_bread_sizes');
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
        Schema::dropIfExists('bake_bread_size_bake_breads');
    }
}
