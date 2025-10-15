<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('debate_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('debate_matches')->onDelete('cascade');
            $table->foreignId('team_member_id')->constrained('team_members')->onDelete('cascade');
            $table->foreignId('judge_id')->nullable()->constrained('users')->onDelete('set null');

            // Score (typically 70-80 range for debate)
            $table->decimal('score', 5, 2);

            // BP Position (PM, DPM, LO, DLO, MG, MO, GW, OW)
            $table->string('bp_position')->nullable();

            // Team position in match (OG, OO, CG, CO)
            $table->string('team_position')->nullable();

            // Speaker rank within match (1-8)
            $table->integer('speaker_rank')->nullable();

            $table->timestamps();

            // Unique constraint: one score per team member per match per judge
            $table->unique(['match_id', 'team_member_id', 'judge_id'], 'unique_score');

            // Indexes for performance
            $table->index('match_id');
            $table->index('team_member_id');
            $table->index('judge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debate_scores');
    }
};
