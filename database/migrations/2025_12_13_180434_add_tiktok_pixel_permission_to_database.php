<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission; // এই লাইনটি অবশ্যই উপরে যোগ করতে হবে

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. নতুন অনুমতি তৈরি করুন
        // এটি ডাটাবেসের `permissions` টেবিলে একটি নতুন row যোগ করবে
        Permission::create(['name' => 'tiktok-pixel-setting', 'guard_name' => 'web']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 2. মাইগ্রেশন রোলব্যাক হলে অনুমতিটি ডিলিট করুন
        Permission::where('name', 'tiktok-pixel-setting')->delete();
    }
};