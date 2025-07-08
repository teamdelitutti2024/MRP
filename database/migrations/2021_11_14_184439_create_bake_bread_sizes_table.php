<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBakeBreadSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bake_bread_sizes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('production_time')->nullable();
            $table->string('complexity')->nullable();
            $table->timestamps();
            $table->integer('bake_bread_id')->unsigned();

            $table->foreign('bake_bread_id')->references('id')->on('bake_breads');
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
        Schema::dropIfExists('bake_bread_sizes');
    }
}
