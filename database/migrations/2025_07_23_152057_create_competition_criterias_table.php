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
        Schema::create('competition_criterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->string('criteria_name'); // Matter, Manner, Method, etc
            $table->text('description'); // Deskripsi kriteria
            $table->integer('weight_percentage'); // Bobot penilaian dalam persen
            $table->json('sub_criteria')->nullable(); // Sub kriteria dalam JSON
            $table->integer('max_score')->default(100); // Skor maksimal
            $table->integer('order_index')->default(0);
            $table->timestamps();
            
            $table->index(['competition_id', 'order_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_criterias');
    }
};
