<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Inventory Logs: Tracks all stock movements
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('type'); // purchase, sale, return, adjustment, cancel
            $table->integer('quantity'); // Positive for Add, Negative for Deduct (can store absolute value and use type to determine sign, but +/- is easier for sum)
            $table->string('ref_id')->nullable(); // Order ID or Purchase ID
            $table->text('note')->nullable();
            $table->integer('current_stock')->nullable(); // Snapshot of stock after transaction
            $table->timestamps();

            // Foreign key usually good, but products might be soft deleted. Let's keep it simple.
        });

        // 2. Purchases: Stock In Challans
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name')->nullable();
            $table->string('challan_no')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        // 3. Purchase Details: Items in a challan
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_details');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('inventory_logs');
    }
}
