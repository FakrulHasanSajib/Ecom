<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tiktok_pixels', function (Blueprint $table) {
            $table->id();
            $table->string('pixel_id'); // TikTok Pixel ID
            $table->text('access_token')->nullable(); // TikTok Access Token (Conversion API)
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tiktok_pixels');
    }
};