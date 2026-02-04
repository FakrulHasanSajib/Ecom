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
    Schema::table('orders', function (Blueprint $table) {
        $table->string('fbp')->nullable()->after('user_agent'); // Facebook Browser ID
        $table->string('fbc')->nullable()->after('fbp');        // Facebook Click ID
        $table->string('ttp')->nullable()->after('fbc');        // TikTok Click ID
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['fbp', 'fbc', 'ttp']);
    });
}
};
