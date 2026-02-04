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
        // ডিফল্ট 'admin' রাখলাম, মানে শুরুতে সব আগের মতই কাজ করবে
        $table->string('pixel_trigger_type')->default('admin')->nullable(); 
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
            //
        });
    }
};
