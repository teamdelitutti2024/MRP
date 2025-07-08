<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBakeBreadSizeResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bake_bread_size_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('production_time', 10, 2);
            $table->integer('quantity_to_produce');
            $table->timestamps();
            $table->integer('bake_bread_size_id')->unsigned();
            $table->integer('resource_id')->unsigned();

            $table->foreign('bake_bread_size_id')->references('id')->on('bake_bread_sizes');
            $table->foreign('resource_id')->references('id')->on('resources');
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
        Schema::dropIfExists('bake_bread_size_resources');
    }
}
