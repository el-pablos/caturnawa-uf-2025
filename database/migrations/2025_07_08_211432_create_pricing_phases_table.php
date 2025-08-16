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
        Schema::create('pricing_phases', function (Blueprint $table) {
            $table->id();
            $table->string('phase_name'); // Early Bird, Phase 1, Phase 2, Phase 3
            $table->enum('participant_category', ['unas_student', 'external_student', 'high_school_student']);
            $table->decimal('amount', 10, 2); // Price in Indonesian Rupiah
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['start_date', 'end_date']);
            $table->index(['participant_category', 'is_active']);
            $table->index(['phase_name', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_phases');
    }
};
