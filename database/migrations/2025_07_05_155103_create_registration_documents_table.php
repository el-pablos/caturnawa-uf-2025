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
        Schema::create('registration_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', ['photo', 'card', 'follow_proof', 'twibbon']);
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['registration_id', 'document_type']);
            $table->index(['document_type', 'is_verified']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_documents');
    }
};
