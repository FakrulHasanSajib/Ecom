<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // আমরা এখানে Schema::create লিখছি না, Schema::table লিখছি
        // অর্থাৎ এটি users টেবিলকে এডিট করবে
        Schema::table('users', function (Blueprint $table) {
            
            // ১. কমিশন পারসেন্টেজ (যদি কলামটি না থাকে তবেই অ্যাড করবে)
            if (!Schema::hasColumn('users', 'commission_rate')) {
                $table->decimal('commission_rate', 10, 2)->default(0.00)->nullable()->after('user_type');
            }

            // ২. ফিক্সড কমিশন
            if (!Schema::hasColumn('users', 'fixed_commission')) {
                $table->decimal('fixed_commission', 10, 2)->default(0.00)->nullable()->after('commission_rate');
            }

            // ৩. ব্যালেন্স
            if (!Schema::hasColumn('users', 'balance')) {
                $table->decimal('balance', 15, 2)->default(0.00)->nullable()->after('fixed_commission');
            }
        });

        // Orders টেবিলে অ্যাসাইন কলাম যোগ করা
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'assign_user_id')) {
                $table->unsignedBigInteger('assign_user_id')->nullable()->after('user_id');
            }
        });
    }

    public function down()
    {
        // যদি মাইগ্রেশন রোলব্যাক (undo) করেন, তখন কলামগুলো মুছে যাবে
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['commission_rate', 'fixed_commission', 'balance']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['assign_user_id']);
        });
    }
};