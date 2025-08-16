<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel submissions
 * 
 * Membuat tabel untuk karya peserta
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->json('files')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->boolean('is_final')->default(false);
            $table->text('submission_notes')->nullable();
            $table->bigInteger('file_size')->default(0);
            $table->integer('file_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
