<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveProductionFieldsFromOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['production_responsible_id']);
            $table->dropForeign(['bake_breads_production_responsible_id']);

            $table->dropColumn(['production_responsible_id', 'bake_breads_production_responsible_id', 'bake_breads_reported', 'products_reported']);
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
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
