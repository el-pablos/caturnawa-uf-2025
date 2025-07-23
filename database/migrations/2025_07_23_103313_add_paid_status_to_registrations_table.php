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
        // Add 'paid' status to registrations status enum
        DB::statement("ALTER TABLE registrations MODIFY COLUMN status ENUM('pending','paid','confirmed','cancelled','expired') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'paid' status from registrations status enum
        DB::statement("ALTER TABLE registrations MODIFY COLUMN status ENUM('pending','confirmed','cancelled','expired') NOT NULL DEFAULT 'pending'");
    }
};
