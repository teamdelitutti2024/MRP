<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreparedProductResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prepared_product_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('production_time', 10, 2);
            $table->integer('quantity_to_produce');
            $table->timestamps();
            $table->integer('prepared_product_id')->unsigned();
            $table->integer('resource_id')->unsigned();

            $table->foreign('prepared_product_id')->references('id')->on('prepared_products');
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
        Schema::dropIfExists('prepared_product_resources');
    }
}
