<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
 public function up()
{
    Schema::create('courier_ledgers', function (Blueprint $table) {
        $table->id();
        $table->string('courier_name'); // যেমন: Steadfast
        $table->string('sheet_name'); // ফাইলের নাম
        $table->decimal('total_credit', 15, 2)->default(0); // কত টাকা পেলেন
        $table->integer('delivered_orders')->default(0);
        $table->integer('returned_orders')->default(0);
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
        Schema::dropIfExists('courier_ledgers');
    }
};
