<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealer_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dealer_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('dealer_price', 10, 2)->default(0.00)->comment('Price set by Admin for Dealer');
            $table->decimal('commission_amount', 10, 2)->default(0.00)->comment('Commission for Referral');
            $table->enum('commission_type', ['fixed', 'percent'])->default('fixed');
            $table->timestamps();

            $table->foreign('dealer_id')->references('id')->on('dealers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealer_products');
    }
};
