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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('log_name')->nullable(); // Category: auth, registration, payment, submission, scoring, admin, etc.
            $table->text('description'); // Human-readable description
            $table->string('subject_type')->nullable(); // Model class name (e.g., App\Models\Registration)
            $table->unsignedBigInteger('subject_id')->nullable(); // Model ID
            $table->string('event')->nullable(); // created, updated, deleted, etc.
            $table->string('causer_type')->nullable(); // Who caused this (usually User)
            $table->unsignedBigInteger('causer_id')->nullable(); // User ID who caused this
            $table->json('properties')->nullable(); // Additional data (old/new values, metadata)
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();

            // Indexes for performance
            $table->index(['user_id', 'created_at']);
            $table->index(['log_name', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
            $table->index(['causer_type', 'causer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
