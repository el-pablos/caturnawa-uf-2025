<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Safely add phase column to scores table for SPC two-phase evaluation
 * This migration only adds the phase column without modifying existing constraints
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only add phase column if it doesn't exist
        if (!Schema::hasColumn('scores', 'phase')) {
            Schema::table('scores', function (Blueprint $table) {
                $table->string('phase')->nullable()->after('jury_id')
                      ->comment('Phase for SPC competition: naskah or presentasi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            if (Schema::hasColumn('scores', 'phase')) {
                $table->dropColumn('phase');
            }
        });
    }
};
