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
            RolePermissionSeeder::class,       // Only superadmin role
            UnasFestCompetitionSeeder::class,  // Updated competition categories
        ]);

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('🎯 All seeders have been executed in the correct order.');
    }
}