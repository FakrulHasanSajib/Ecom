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
    Schema::create('visitor_activities', function (Blueprint $table) {
        $table->id();
        $table->string('session_id')->index(); // ইউনিক সেশন আইডি
        $table->string('ip_address')->nullable();
        $table->string('url');
        $table->integer('time_spent')->default(0); // সেকেন্ডে
        $table->integer('scroll_depth')->default(0); // শতাংশে (0-100%)
        $table->date('date');
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
        Schema::dropIfExists('visitor_activities');
    }
};
