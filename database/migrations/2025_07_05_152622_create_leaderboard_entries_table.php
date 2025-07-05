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
        Schema::create('leaderboard_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->string('team_name');
            $table->string('participant_name');
            $table->string('institution')->nullable();
            $table->decimal('score', 8, 2)->default(0);
            $table->integer('victory_points')->default(0);
            $table->integer('rank')->default(0);
            $table->enum('rank_type', ['position', 'mention'])->default('position');
            $table->boolean('is_active')->default(true);
            $table->timestamp('computed_at');
            $table->timestamps();

            $table->index(['competition_id', 'rank']);
            $table->index(['competition_id', 'score']);
            $table->unique(['competition_id', 'registration_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard_entries');
    }
};
