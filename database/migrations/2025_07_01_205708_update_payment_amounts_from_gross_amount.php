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
        // Update existing records to sync amount with gross_amount where amount is 0 or null
        DB::statement("
            UPDATE payments
            SET amount = gross_amount
            WHERE amount IS NULL OR amount = 0
        ");

        // Update payment_method from payment_type where payment_method is null
        DB::statement("
            UPDATE payments
            SET payment_method = payment_type
            WHERE payment_method IS NULL AND payment_type IS NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data fix
    }
};
