<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to modify scores table for SPC two-phase evaluation system
 * 
 * Removes the unique constraint that prevents multiple scores per jury-participant
 * and adds a phase field to differentiate between Naskah and Presentasi evaluations
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if phase column exists, if not add it
        if (!Schema::hasColumn('scores', 'phase')) {
            Schema::table('scores', function (Blueprint $table) {
                $table->string('phase')->nullable()->after('jury_id');
            });
        }
        
        // Handle unique constraint modification safely
        Schema::table('scores', function (Blueprint $table) {
            try {
                // Check if the unique constraint exists before trying to drop it
                $table->dropUnique(['competition_id', 'registration_id', 'jury_id']);
            } catch (\Exception $e) {
                // Ignore if constraint doesn't exist or can't be dropped
            }
            
            try {
                // Also try dropping by index name if it exists
                $table->dropIndex('scores_competition_id_registration_id_jury_id_unique');
            } catch (\Exception $e) {
                // Ignore if index doesn't exist
            }
        });
        
        // Create new unique constraint that includes phase in a separate operation
        Schema::table('scores', function (Blueprint $table) {
            // This allows multiple scores per jury-participant but only one per phase
            $table->unique(['competition_id', 'registration_id', 'jury_id', 'phase'], 'scores_unique_per_phase');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            // Drop the new unique constraint
            $table->dropUnique('scores_unique_per_phase');
            
            // Remove the phase field
            $table->dropColumn('phase');
            
            // Restore the original unique constraint
            $table->unique(['competition_id', 'registration_id', 'jury_id']);
        });
    }
};
