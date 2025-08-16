<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel round_matches
 *
 * Menyimpan pertandingan dalam setiap babak kompetisi
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('round_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_round_id')->constrained()->onDelete('cascade');
            $table->string('match_name'); // e.g., "Round 1", "Round 2"
            $table->string('room_name')->nullable(); // e.g., "Breakout Room 1"
            $table->text('motion')->nullable(); // mosi debat
            $table->datetime('scheduled_at')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->json('settings')->nullable(); // pengaturan khusus match
            $table->timestamps();

            $table->index(['competition_round_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('round_matches');
    }
};
