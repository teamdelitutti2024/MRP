<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierBankDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_bank_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank', 120);
            $table->string('account_holder', 120);
            $table->string('account_number', 120)->nullable();
            $table->string('clabe')->nullable();
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
        Schema::dropIfExists('supplier_bank_data');
    }
}
