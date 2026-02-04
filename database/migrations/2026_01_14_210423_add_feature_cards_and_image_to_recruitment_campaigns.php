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
        Schema::table('recruitment_campaigns', function (Blueprint $table) {
            $table->text('header_text')->nullable()->after('slug');
        $table->string('agent_video_id')->nullable()->after('video_url');
        $table->string('khadem_video_id')->nullable()->after('agent_video_id');
        $table->string('login_url')->nullable()->after('description');
        $table->string('register_url')->nullable()->after('login_url');
        
        // ইমেজগুলোর জন্য কলাম
        $table->string('image_one')->nullable()->after('banner');
        $table->string('image_two')->nullable()->after('image_one');
        $table->string('image_three')->nullable()->after('image_two');
        $table->string('feature_section_image')->nullable()->after('image_three');

        // কার্ড ১: কোরআন খাদেম
        $table->string('card_1_title')->nullable()->after('description');
        $table->text('card_1_desc')->nullable()->after('card_1_title');

        // কার্ড ২: লাইব্রেরিয়ান
        $table->string('card_2_title')->nullable()->after('card_1_desc');
        $table->text('card_2_desc')->nullable()->after('card_2_title');

        // কার্ড ৩: এজেন্ট
        $table->string('card_3_title')->nullable()->after('card_2_desc');
        $table->text('card_3_desc')->nullable()->after('card_3_title');

        // কার্ড ৪: কর্পোরেট গিফট
        $table->string('card_4_title')->nullable()->after('card_3_desc');
        $table->text('card_4_desc')->nullable()->after('card_4_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruitment_campaigns', function (Blueprint $table) {
     $table->dropColumn([
            'header_text', 
            'agent_video_id', 
            'khadem_video_id', 
            'login_url', 
            'register_url', 
            'image_one', 
            'image_two', 
            'image_three',
            'feature_section_image',
            'card_1_title', 'card_1_desc',
            'card_2_title', 'card_2_desc',
            'card_3_title', 'card_3_desc',
            'card_4_title', 'card_4_desc',
        ]);
    });
}
};