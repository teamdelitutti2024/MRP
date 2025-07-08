<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteBakeBreadFieldsFromProductSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->dropForeign(['bake_bread_size_id']);
            $table->dropForeign(['secondary_bake_bread_size_id']);
            $table->dropColumn(['bake_bread_size_id', 'secondary_bake_bread_size_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            //
        });
    }
}
