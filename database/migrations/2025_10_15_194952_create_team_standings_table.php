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
        Schema::create('team_standings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->unique()->constrained('registrations')->onDelete('cascade');

            // Overall statistics
            $table->integer('matches_played')->default(0);
            $table->integer('team_points')->default(0);
            $table->decimal('speaker_points', 8, 2)->default(0);
            $table->decimal('average_speaker_points', 8, 2)->default(0);
            $table->decimal('avg_position', 8, 2)->default(0);

            // Position counts
            $table->integer('first_places')->default(0);
            $table->integer('second_places')->default(0);
            $table->integer('third_places')->default(0);
            $table->integer('fourth_places')->default(0);

            // Stage-specific statistics (Preliminary)
            $table->integer('prelim_team_points')->default(0);
            $table->decimal('prelim_speaker_points', 8, 2)->default(0);
            $table->decimal('prelim_avg_position', 8, 2)->default(0);

            // Stage-specific statistics (Semifinal)
            $table->integer('semifinal_team_points')->default(0);
            $table->decimal('semifinal_speaker_points', 8, 2)->default(0);
            $table->decimal('semifinal_avg_position', 8, 2)->default(0);

            // Stage-specific statistics (Final)
            $table->integer('final_team_points')->default(0);
            $table->decimal('final_speaker_points', 8, 2)->default(0);
            $table->decimal('final_avg_position', 8, 2)->default(0);

            $table->timestamps();

            // Indexes for leaderboard queries
            $table->index('team_points');
            $table->index('speaker_points');
            $table->index('avg_position');
            $table->index(['team_points', 'speaker_points', 'avg_position'], 'leaderboard_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_standings');
    }
};
