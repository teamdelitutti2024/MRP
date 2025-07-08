<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecondaryBakeBreadSizeIdToProductSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->integer('secondary_bake_bread_size_id')->unsigned()->nullable()->after('bake_bread_size_id');

            $table->foreign('secondary_bake_bread_size_id')->references('id')->on('bake_bread_sizes');
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
        Schema::table('product_sizes', function (Blueprint $table) {
            //
        });
    }
}
