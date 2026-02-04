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
    Schema::create('recruitment_campaigns', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        
        // 🔥 আসল ম্যাজিক: ডিলারের আইডি এখানে থাকবে
        $table->string('referral_code')->nullable(); 
        
        // কে বানিয়েছে (Admin না Dealer)
        $table->string('creator_type')->default('admin'); 
        $table->unsignedBigInteger('creator_id')->nullable(); 

        // পেজের কন্টেন্ট
        $table->string('banner')->nullable();
        $table->string('video_url')->nullable(); // Youtube Link
        $table->longText('description')->nullable();
        
        $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('recruitment_campaigns');
    }
};
