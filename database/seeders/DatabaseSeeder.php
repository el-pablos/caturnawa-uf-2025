<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder untuk data awal sistem
 *
 * Hanya membuat superadmin user
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
            SuperAdminSeeder::class,  // Superadmin user and role
            MissingRolesSeeder::class,  // Create missing roles (admin, peserta, juri)
            CompetitionDetailSeeder::class,  // All competitions with detailed requirements and criteria
        ]);

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('🎯 Only superadmin user has been created.');
    }
}