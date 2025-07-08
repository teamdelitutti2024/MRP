<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldsFromStockLevel1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_level_1', function (Blueprint $table) {
            $table->dropForeign(['responsible_id']);
            $table->dropColumn(['valid_until', 'responsible_id', 'product_name', 'product_size_name', 'remaining_quantity', 'price']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_level_1', function (Blueprint $table) {
            //
        });
    }
}
