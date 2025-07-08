<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddComplexityProductionTimeToProductSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->string('complexity')->nullable()->after('sale_price');
            $table->decimal('production_time', 10, 2)->nullable()->after('sale_price');
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
