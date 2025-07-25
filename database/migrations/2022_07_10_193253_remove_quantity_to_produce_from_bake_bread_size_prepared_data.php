<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveQuantityToProduceFromBakeBreadSizePreparedData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bake_bread_size_prepared_data', function (Blueprint $table) {
            $table->dropColumn('quantity_to_produce');
            $table->decimal('quantity', 10, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bake_bread_size_prepared_data', function (Blueprint $table) {
            //
        });
    }
}
