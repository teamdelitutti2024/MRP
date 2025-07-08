<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBakeBreadSizeKeyToBakeBreadSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bake_bread_sizes', function (Blueprint $table) {
            $table->string('bake_bread_size_key')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bake_bread_sizes', function (Blueprint $table) {
            //
        });
    }
}
