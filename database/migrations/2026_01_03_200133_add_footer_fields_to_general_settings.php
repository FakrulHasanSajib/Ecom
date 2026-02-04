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
        $table->string('footer_text')->nullable()->default('EiconBD');
        $table->string('footer_link')->nullable()->default('https://eiconbd.com/');
    });
}

public function down()
{
    Schema::table('general_settings', function (Blueprint $table) {
        $table->dropColumn(['footer_text', 'footer_link']);
    });
}
};
