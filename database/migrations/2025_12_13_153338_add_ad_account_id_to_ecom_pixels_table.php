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
        Schema::table('ecom_pixels', function (Blueprint $table) {
            // 'meta_access_token'-এর পরে 'ad_account_id' নামে নতুন কলাম যোগ করা হলো
            // এটি act_xxxxxx ফরম্যাটের স্ট্রিং রাখবে এবং null হতে পারবে।
            $table->string('ad_account_id')->nullable()->after('meta_access_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecom_pixels', function (Blueprint $table) {
            // রোলব্যাকের সময় কলামটি সরিয়ে ফেলা হলো
            $table->dropColumn('ad_account_id');
        });
    }
};