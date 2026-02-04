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
    Schema::table('resellers', function (Blueprint $table) {
        $table->string('user_role')->default('reseller')->after('id'); // সেলার বা লাইব্রেরিয়ান আলাদা করতে
        $table->integer('district_id')->nullable()->after('address');
        $table->integer('thana_id')->nullable()->after('district_id');
        // status কলাম আগে থেকেই থাকলে এটি বাদ দিন, নাহলে নতুন করে যোগ করুন
        // $table->string('status')->default('pending'); 
    });
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resellers', function (Blueprint $table) {
            //
        });
    }
};
