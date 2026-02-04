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
        Schema::table('general_settings', function (Blueprint $table) {
            // হেডার এবং ফুটার স্ক্রিপ্টের জন্য দুটি নতুন কলাম যোগ করা হচ্ছে
            $table->longText('header_script')->nullable()->after('status');
            $table->longText('footer_script')->nullable()->after('header_script');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            // রোলব্যাক করলে কলামগুলো ডিলিট হয়ে যাবে
            $table->dropColumn(['header_script', 'footer_script']);
        });
    }
};