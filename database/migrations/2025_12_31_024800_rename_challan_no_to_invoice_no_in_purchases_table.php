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
        Schema::table('purchases', function (Blueprint $table) {
            // Check if column exists before renaming to avoid errors if it was already manually changed
            if (Schema::hasColumn('purchases', 'challan_no')) {
                // Using raw statement to avoid doctrine/dbal requirement for renaming
                DB::statement("ALTER TABLE purchases CHANGE challan_no invoice_no VARCHAR(191) NULL");
            } else {
                // Fallback: if challan_no doesn't exist, create invoice_no if missing
                if (!Schema::hasColumn('purchases', 'invoice_no')) {
                    $table->string('invoice_no')->nullable()->after('supplier_name');
                }
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
        Schema::table('purchases', function (Blueprint $table) {
            if (Schema::hasColumn('purchases', 'invoice_no')) {
                $table->renameColumn('invoice_no', 'challan_no');
            }
        });
    }
};
