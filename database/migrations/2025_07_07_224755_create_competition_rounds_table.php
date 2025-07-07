<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel competition_rounds
 *
 * Menyimpan babak-babak kompetisi (penyisihan, semifinal, final)
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('competition_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->enum('round_type', ['penyisihan', 'semifinal', 'final']);
            $table->string('name'); // e.g., "Babak Penyisihan", "Semifinal", "Final"
            $table->text('description')->nullable();
            $table->integer('round_number')->default(1); // untuk multiple rounds dalam satu babak
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed'])->default('upcoming');
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // untuk pengaturan khusus per babak
            $table->timestamps();

            $table->index(['competition_id', 'round_type']);
            $table->unique(['competition_id', 'round_type', 'round_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_rounds');
    }
};
