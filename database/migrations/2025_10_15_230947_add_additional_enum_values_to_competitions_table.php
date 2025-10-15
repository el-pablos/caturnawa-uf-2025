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
        // Add additional enum values for category to support test cases
        DB::statement("ALTER TABLE competitions MODIFY COLUMN category ENUM('event_dcc', 'event_debate', 'event_scientific_paper', 'debate', 'essay', 'dcc', 'spc') NOT NULL");

        // Add additional enum values for status to support test cases
        DB::statement("ALTER TABLE competitions MODIFY COLUMN status ENUM('active', 'inactive', 'draft', 'completed', 'open', 'closed') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE competitions MODIFY COLUMN category ENUM('event_dcc', 'event_debate', 'event_scientific_paper') NOT NULL");
        DB::statement("ALTER TABLE competitions MODIFY COLUMN status ENUM('active', 'inactive', 'draft', 'completed') DEFAULT 'active'");
    }
};
