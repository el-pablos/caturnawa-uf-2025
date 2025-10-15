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
        Schema::table('users', function (Blueprint $table) {
            // Social Media Links
            $table->string('linkedin_url')->nullable()->after('bio');
            $table->string('twitter_url')->nullable()->after('linkedin_url');
            $table->string('instagram_url')->nullable()->after('twitter_url');
            $table->string('facebook_url')->nullable()->after('instagram_url');
            $table->string('github_url')->nullable()->after('facebook_url');
            $table->string('website_url')->nullable()->after('github_url');

            // Achievement Badges (JSON array)
            $table->json('badges')->nullable()->after('website_url');

            // Profile Completion
            $table->integer('profile_completion')->default(0)->after('badges');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'linkedin_url',
                'twitter_url',
                'instagram_url',
                'facebook_url',
                'github_url',
                'website_url',
                'badges',
                'profile_completion',
            ]);
        });
    }
};
