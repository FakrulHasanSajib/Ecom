<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('resellers', function (Blueprint $table) {
            $table->integer('referrer_id')->nullable()->after('dealer_id');
        });
    }

    public function down()
    {
        Schema::table('resellers', function (Blueprint $table) {
            $table->dropColumn('referrer_id');
        });
    }
};
