<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Add status column as alias for transaction_status
            $table->string('status')->default('pending')->after('transaction_status');
            
            // Add amount column as alias for gross_amount
            $table->decimal('amount', 10, 2)->default(0)->after('gross_amount');
            
            // Add payment_method column
            $table->string('payment_method')->nullable()->after('payment_type');
            
            // Add bank column for VA payments
            $table->string('bank')->nullable()->after('payment_method');
            
            // Add va_number column for VA payments
            $table->string('va_number')->nullable()->after('bank');
        });
        
        // Update existing records to sync status with transaction_status
        DB::statement("
            UPDATE payments 
            SET status = CASE 
                WHEN transaction_status IN ('settlement', 'capture') THEN 'paid'
                WHEN transaction_status = 'pending' THEN 'pending'
                WHEN transaction_status IN ('deny', 'cancel', 'expire', 'failure') THEN 'failed'
                ELSE 'pending'
            END
        ");
        
        // Update existing records to sync amount with gross_amount
        DB::statement("UPDATE payments SET amount = gross_amount");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['status', 'amount', 'payment_method', 'bank', 'va_number']);
        });
    }
};
