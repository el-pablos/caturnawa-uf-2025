<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel competition_scoring_criteria
 *
 * Menyimpan kriteria penilaian untuk setiap kompetisi
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('competition_scoring_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->string('criteria_name'); // e.g., "Content", "Delivery", "Strategy"
            $table->text('description')->nullable();
            $table->decimal('max_score', 5, 2); // skor maksimal untuk kriteria ini
            $table->decimal('weight', 5, 2)->default(1.00); // bobot kriteria (untuk weighted scoring)
            $table->integer('order')->default(0); // urutan tampilan
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['competition_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_scoring_criteria');
    }
};
