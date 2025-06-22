<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel scores
 * 
 * Membuat tabel penilaian juri
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('jury_id')->constrained('users')->onDelete('cascade');
            $table->json('criteria_scores')->nullable();
            $table->decimal('total_score', 5, 2)->default(0);
            $table->text('comments')->nullable();
            $table->boolean('is_final')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            // Pastikan satu juri hanya bisa menilai satu peserta sekali per kompetisi
            $table->unique(['competition_id', 'registration_id', 'jury_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
