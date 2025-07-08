<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeclinedPreparedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declined_prepared_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->string('prepared_product_name', 100);
            $table->text('comments')->nullable();
            $table->integer('prepared_product_id')->unsigned();
            $table->integer('responsible_id')->unsigned();
            $table->timestamps();

            $table->foreign('prepared_product_id')->references('id')->on('prepared_products');
            $table->foreign('responsible_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('declined_prepared_products');
    }
}
