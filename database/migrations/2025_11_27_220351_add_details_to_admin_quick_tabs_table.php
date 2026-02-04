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
        // ⭐ টেবিলকে আপডেট করতে Schema::table ব্যবহার করা হয়েছে
        Schema::table('admin_quick_tabs', function (Blueprint $table) {
            
            // 1. user_id যোগ করা
            if (!Schema::hasColumn('admin_quick_tabs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id');
                $table->index('user_id');
                // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            
            // 2. tab_name যোগ করা
            if (!Schema::hasColumn('admin_quick_tabs', 'tab_name')) {
                 $table->string('tab_name')->nullable()->after('user_id');
            }
            
            // 3. tab_link যোগ করা
            if (!Schema::hasColumn('admin_quick_tabs', 'tab_link')) {
                 $table->string('tab_link')->nullable()->after('tab_name');
            }
            
            // 4. is_active যোগ করা
             if (!Schema::hasColumn('admin_quick_tabs', 'is_active')) {
                 $table->boolean('is_active')->default(1)->after('tab_link');
            }

            // 5. order যোগ করা
             if (!Schema::hasColumn('admin_quick_tabs', 'order')) {
                 $table->integer('order')->default(0)->after('is_active');
            }
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // ⭐ Rollback করার সময় কলামগুলি মুছে ফেলা হবে
        Schema::table('admin_quick_tabs', function (Blueprint $table) {
            // Drop column 'order' first as it was added last
            if (Schema::hasColumn('admin_quick_tabs', 'order')) {
                $table->dropColumn('order');
            }
            
            // Drop column 'is_active'
            if (Schema::hasColumn('admin_quick_tabs', 'is_active')) {
                $table->dropColumn('is_active');
            }
            
            // Drop column 'tab_link'
            if (Schema::hasColumn('admin_quick_tabs', 'tab_link')) {
                $table->dropColumn('tab_link');
            }
            
            // Drop column 'tab_name'
            if (Schema::hasColumn('admin_quick_tabs', 'tab_name')) {
                $table->dropColumn('tab_name');
            }
            
            // Drop column 'user_id' (must be dropped last among these)
            if (Schema::hasColumn('admin_quick_tabs', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};