<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsFromDeclinedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('declined_products', function (Blueprint $table) {
            $table->dropForeign(['stock_level_1_id']);
            $table->dropColumn('stock_level_1_id');
            $table->date('product_date')->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('declined_products', function (Blueprint $table) {
            //
        });
    }
}
