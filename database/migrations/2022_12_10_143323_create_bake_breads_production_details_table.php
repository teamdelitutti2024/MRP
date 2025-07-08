<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBakeBreadsProductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bake_breads_production_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bake_bread_size_name', 100);
            $table->integer('quantity');
            $table->integer('bake_bread_size_id')->unsigned();
            $table->integer('bake_breads_production_id')->unsigned();
            $table->timestamps();

            $table->foreign('bake_bread_size_id')->references('id')->on('bake_bread_sizes');
            $table->foreign('bake_breads_production_id')->references('id')->on('bake_breads_production');
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
        Schema::dropIfExists('bake_breads_production_details');
    }
}
