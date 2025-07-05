<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel competitions
 * 
 * Membuat tabel kompetisi dengan semua field yang diperlukan
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('category', ['biodiversity', 'health', 'technology']);
            $table->string('theme')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('early_bird_price', 10, 2)->nullable();
            $table->datetime('early_bird_deadline')->nullable();
            $table->datetime('registration_start');
            $table->datetime('registration_end');
            $table->datetime('competition_start');
            $table->datetime('competition_end');
            $table->datetime('submission_deadline')->nullable();
            $table->datetime('result_announcement')->nullable();
            $table->integer('max_participants')->nullable();
            $table->integer('min_team_members')->nullable();
            $table->integer('max_team_members')->nullable();
            $table->json('requirements')->nullable();
            $table->json('prizes')->nullable();
            $table->json('rules')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_team_competition')->default(false);
            $table->boolean('allow_individual')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
