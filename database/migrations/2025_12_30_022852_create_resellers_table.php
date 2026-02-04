<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resellers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->length('255');
            $table->string('email', 55)->unique()->nullable();
            $table->string('phone', 55)->unique();
            $table->string('store_name')->length('255')->nullable();
            $table->text('address')->nullable();
            $table->string('password')->length('255');
            $table->string('image')->default('default/user.png');
            $table->decimal('balance', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('verify_token')->nullable();
            $table->integer('dealer_id')->nullable();
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
        Schema::dropIfExists('resellers');
    }
};
