<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsFromStockLevel1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_level_1', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('product_name', 100);
            $table->string('product_size_name', 100);
            $table->integer('quantity');
            $table->integer('remaining_quantity');
            $table->decimal('price', 10, 3);
            $table->text('comments')->nullable();
            $table->integer('product_id')->unsigned();
            $table->integer('product_size_id')->unsigned();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('product_size_id')->references('id')->on('product_sizes');
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
        Schema::table('stock_level_1', function (Blueprint $table) {
            //
        });
    }
}
