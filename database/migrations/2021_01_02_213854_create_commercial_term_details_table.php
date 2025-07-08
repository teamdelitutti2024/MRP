<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommercialTermDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commercial_term_details', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('percentage', 3, 2);
            $table->integer('days');
            $table->timestamps();
            $table->integer('commercial_term_id')->unsigned();

            $table->foreign('commercial_term_id')->references('id')->on('commercial_terms');
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
        Schema::dropIfExists('commercial_term_details');
    }
}
