<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierTaxDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_tax_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('business_reason', 120);
            $table->string('RFC');
            $table->string('street', 120)->nullable();
            $table->string('outside_number')->nullable();
            $table->string('colony')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
            $table->integer('supplier_id')->unsigned();

            $table->foreign('supplier_id')->references('id')->on('suppliers');
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
        Schema::dropIfExists('supplier_tax_data');
    }
}
