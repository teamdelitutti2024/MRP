<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquivalencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equivalences', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('equivalence', 10, 2);
            $table->timestamps();
            $table->integer('source_measurement_id')->unsigned();
            $table->integer('target_measurement_id')->unsigned();

            $table->foreign('source_measurement_id')->references('id')->on('measurement_units');
            $table->foreign('target_measurement_id')->references('id')->on('measurement_units');
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
        Schema::dropIfExists('equivalences');
    }
}
