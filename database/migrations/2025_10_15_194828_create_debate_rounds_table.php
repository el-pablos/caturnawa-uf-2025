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
        Schema::create('debate_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competitions')->onDelete('cascade');
            $table->enum('stage', ['PRELIMINARY', 'SEMIFINAL', 'FINAL']);
            $table->integer('round_number');
            $table->integer('session')->default(1);
            $table->string('round_name');
            $table->text('motion')->nullable();
            $table->boolean('is_frozen')->default(false);
            $table->timestamp('frozen_at')->nullable();
            $table->string('frozen_by')->nullable();
            $table->timestamps();

            // Unique constraint: one round per competition/stage/number/session
            $table->unique(['competition_id', 'stage', 'round_number', 'session'], 'unique_round');

            // Indexes for performance
            $table->index(['competition_id', 'stage']);
            $table->index('is_frozen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debate_rounds');
    }
};
