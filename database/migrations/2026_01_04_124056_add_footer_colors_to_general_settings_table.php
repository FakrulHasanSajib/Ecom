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
        // এই ৩টি লাইন যোগ করুন
        $table->string('footer_color_1')->nullable()->default('#1a1a1a')->after('status');
        $table->string('footer_color_2')->nullable()->default('#0e4f35')->after('footer_color_1');
        $table->string('footer_color_3')->nullable()->default('#082e1f')->after('footer_color_2');
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
        $table->dropColumn(['footer_color_1', 'footer_color_2', 'footer_color_3']);
    });
}
};
