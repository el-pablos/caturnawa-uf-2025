<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel team_matchups
 *
 * Menyimpan penjadwalan tim dalam setiap pertandingan
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('team_matchups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_match_id')->constrained()->onDelete('cascade');
            $table->foreignId('registration_id')->constrained()->onDelete('cascade'); // tim yang bertanding
            $table->enum('position', ['OG', 'OO', 'CG', 'CO']); // posisi debat
            $table->foreignId('jury_id')->nullable()->constrained('users')->onDelete('set null'); // juri yang menilai
            $table->decimal('team_score', 5, 2)->nullable(); // skor tim
            $table->integer('victory_points')->default(0); // victory points
            $table->integer('ranking')->nullable(); // ranking dalam match ini
            $table->json('individual_scores')->nullable(); // skor individual anggota tim
            $table->text('feedback')->nullable(); // feedback dari juri
            $table->timestamps();

            $table->index(['round_match_id', 'position']);
            $table->index(['registration_id', 'victory_points']);
            $table->unique(['round_match_id', 'registration_id']); // satu tim hanya bisa sekali per match
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_matchups');
    }
};
