<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemoveFieldsFromSupplyTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_transfers', function (Blueprint $table) {
            $table->decimal('transaction_amount', 10, 3)->after('transferred_quantity');
            $table->dropColumn(['is_return', 'remaining_quantity', 'measure']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supply_transfers', function (Blueprint $table) {
            //
        });
    }
}
