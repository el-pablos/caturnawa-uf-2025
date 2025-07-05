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
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}