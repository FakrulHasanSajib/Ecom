<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('reseller_id');
            $table->decimal('amount', 10, 2);
            $table->string('method')->comment('bkash, nagad, bank');
            $table->text('account_info');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();

            // Foreign key if desired, but integer is fine for now as per other tables
        });
    }

    public function down()
    {
        Schema::dropIfExists('withdrawals');
    }
};
