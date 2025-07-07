<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration untuk mengubah kategori kompetisi dari teknologi/health/bio
 * menjadi kategori sektor event: Event DCC, Event Debate, Event Scientific Paper
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, modify the column to allow new enum values
        DB::statement("ALTER TABLE competitions MODIFY COLUMN category ENUM('biodiversity', 'health', 'technology', 'event_dcc', 'event_debate', 'event_scientific_paper') NOT NULL");

        // Then update existing data to new categories
        DB::table('competitions')->where('category', 'technology')->update(['category' => 'event_dcc']);
        DB::table('competitions')->where('category', 'health')->update(['category' => 'event_debate']);
        DB::table('competitions')->where('category', 'biodiversity')->update(['category' => 'event_scientific_paper']);

        // Finally, remove old enum values
        DB::statement("ALTER TABLE competitions MODIFY COLUMN category ENUM('event_dcc', 'event_debate', 'event_scientific_paper') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data back to old categories
        DB::table('competitions')->where('category', 'event_dcc')->update(['category' => 'technology']);
        DB::table('competitions')->where('category', 'event_debate')->update(['category' => 'health']);
        DB::table('competitions')->where('category', 'event_scientific_paper')->update(['category' => 'biodiversity']);

        // Restore original enum values
        DB::statement("ALTER TABLE competitions MODIFY COLUMN category ENUM('biodiversity', 'health', 'technology') NOT NULL");
    }
};
