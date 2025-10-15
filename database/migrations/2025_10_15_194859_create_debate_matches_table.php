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
        Schema::create('debate_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained('debate_rounds')->onDelete('cascade');
            $table->integer('match_number');
            $table->string('match_format')->default('BP'); // British Parliamentary

            // Team assignments (4 teams for BP format)
            $table->foreignId('team1_id')->nullable()->constrained('registrations')->onDelete('set null');
            $table->foreignId('team2_id')->nullable()->constrained('registrations')->onDelete('set null');
            $table->foreignId('team3_id')->nullable()->constrained('registrations')->onDelete('set null');
            $table->foreignId('team4_id')->nullable()->constrained('registrations')->onDelete('set null');

            // Judge assignment
            $table->foreignId('judge_id')->nullable()->constrained('users')->onDelete('set null');

            // Room assignment
            $table->string('room_name')->nullable();

            // Match results (team rankings)
            $table->foreignId('first_place_team_id')->nullable()->constrained('registrations')->onDelete('set null');
            $table->foreignId('second_place_team_id')->nullable()->constrained('registrations')->onDelete('set null');
            $table->foreignId('third_place_team_id')->nullable()->constrained('registrations')->onDelete('set null');
            $table->foreignId('fourth_place_team_id')->nullable()->constrained('registrations')->onDelete('set null');

            // Scheduling
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Unique constraint: one match number per round
            $table->unique(['round_id', 'match_number']);

            // Indexes for performance
            $table->index('judge_id');
            $table->index('completed_at');
            $table->index('room_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debate_matches');
    }
};
