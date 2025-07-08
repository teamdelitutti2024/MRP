<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquivalenceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equivalence_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('status');
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
        Schema::dropIfExists('equivalence_logs');
    }
}
