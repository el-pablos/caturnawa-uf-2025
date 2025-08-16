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
        // For MySQL, we need to use raw SQL to modify enum values
        DB::statement("ALTER TABLE submissions CHANGE COLUMN status status ENUM('draft', 'submitted', 'overdue', 'scored', 'approved', 'rejected') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE submissions CHANGE COLUMN status status ENUM('draft', 'submitted', 'overdue', 'scored') DEFAULT 'draft'");
    }
};
