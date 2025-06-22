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
        Schema::table('submissions', function (Blueprint $table) {
            // Add missing columns that are referenced in the application
            $table->string('file_path')->nullable()->after('files');
            $table->string('video_url')->nullable()->after('file_path');
            $table->string('github_url')->nullable()->after('video_url');
            $table->string('preview_image')->nullable()->after('github_url');
            $table->text('technologies')->nullable()->after('preview_image');
            $table->string('team_name')->nullable()->after('technologies');
            $table->json('team_members')->nullable()->after('team_name');
            $table->decimal('score', 5, 2)->nullable()->after('team_members');
            $table->text('feedback')->nullable()->after('score');
            $table->boolean('is_scored')->default(false)->after('feedback');
            $table->timestamp('scored_at')->nullable()->after('is_scored');
            $table->integer('view_count')->default(0)->after('scored_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn([
                'file_path',
                'video_url',
                'github_url',
                'preview_image',
                'technologies',
                'team_name',
                'team_members',
                'score',
                'feedback',
                'is_scored',
                'scored_at',
                'view_count'
            ]);
        });
    }
};
