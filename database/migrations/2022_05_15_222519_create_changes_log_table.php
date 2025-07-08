<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangesLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('changes_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('element_name')->nullable();
            $table->string('element_key')->nullable();
            $table->string('model')->nullable();
            $table->string('event')->nullable();
            $table->decimal('previous_quantity', 10, 3)->nullable();
            $table->decimal('new_quantity', 10, 3)->nullable();
            $table->integer('responsible_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('responsible_id')->references('id')->on('users');
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
        Schema::dropIfExists('log_changes');
    }
}
