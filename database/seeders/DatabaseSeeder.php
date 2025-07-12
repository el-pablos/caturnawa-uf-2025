<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Competition;
use Illuminate\Support\Str;

/**
 * Seeder untuk data awal sistem
 * 
 * Membuat user default dan contoh kompetisi
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting fresh database seeding...');

        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            UnasFestCompetitionSeeder::class,  // Main UNAS Fest competitions
            CompetitionSeeder::class,          // Additional test competitions
            PricingPhaseSeeder::class,
            LeaderboardSeeder::class,          // Dummy leaderboard data for homepage display
        ]);

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('🎯 All seeders have been executed in the correct order.');
    }
}