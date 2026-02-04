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
    Schema::table('google_tag_managers', function (Blueprint $table) {
        $table->string('google_client_id')->nullable();
        $table->string('google_client_secret')->nullable();
        $table->text('google_refresh_token')->nullable();
        $table->string('google_developer_token')->nullable();
        $table->string('google_ads_customer_id')->nullable();
        $table->string('google_conversion_action_id')->nullable();
    });
}

public function down()
{
    Schema::table('google_tag_managers', function (Blueprint $table) {
        $table->dropColumn([
            'google_client_id', 'google_client_secret', 'google_refresh_token',
            'google_developer_token', 'google_ads_customer_id', 'google_conversion_action_id'
        ]);
    });
}
};
