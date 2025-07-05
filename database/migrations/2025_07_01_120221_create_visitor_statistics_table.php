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
        Schema::create('visitor_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index();
            $table->string('user_agent')->nullable();
            $table->string('page_url')->nullable();
            $table->string('referrer')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->boolean('is_unique_today')->default(true);
            $table->timestamp('visited_at');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('visited_at');
            $table->index(['ip_address', 'visited_at']);
            $table->index('is_unique_today');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_statistics');
    }
};
