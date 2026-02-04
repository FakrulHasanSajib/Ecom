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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
        $table->string('filename');      // অরিজিনাল নাম
        $table->string('path');          // ফোল্ডারের লোকেশন (যেমন: uploads/gallery/img1.jpg)
        $table->string('extension');     // jpg, png
        $table->string('mime_type');     // image/jpeg
        $table->unsignedBigInteger('size'); // ফাইল সাইজ
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
};
