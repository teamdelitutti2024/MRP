<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemoveCategoryFieldsFromSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplies', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->integer('supply_category_id')->unsigned()->nullable()->after('measurement_unit_id');

            $table->foreign('supply_category_id')->references('id')->on('supply_categories');
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
        Schema::table('supplies', function (Blueprint $table) {
            //
        });
    }
}
