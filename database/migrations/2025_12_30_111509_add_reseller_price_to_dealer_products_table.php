<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResellerPriceToDealerProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dealer_products', function (Blueprint $table) {
            $table->decimal('reseller_price', 10, 2)->default(0)->after('dealer_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dealer_products', function (Blueprint $table) {
            $table->dropColumn('reseller_price');
        });
    }
}
