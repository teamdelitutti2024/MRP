<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBakeBreadRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bake_bread_recipes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 120)->nullable();
            $table->longText('steps')->nullable();
            $table->longText('extra_steps')->nullable();
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
        Schema::dropIfExists('bake_bread_recipes');
    }
}
