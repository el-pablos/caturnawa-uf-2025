<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder khusus untuk membuat user default tanpa kompetisi
 */
class UserOnlySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat Super Admin jika belum ada
        if (!User::where('email', 'superadmin@unasfest.ac.id')->exists()) {
            $superAdmin = User::create([
                'name' => 'Super Administrator',
                'email' => 'superadmin@unasfest.ac.id',
                'password' => Hash::make('superadmin123'),
                'phone' => '08123456789',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $superAdmin->assignRole('Super Admin');
        }

        // Buat Admin jika belum ada
        if (!User::where('email', 'admin@unasfest.ac.id')->exists()) {
            $admin = User::create([
                'name' => 'Administrator',
                'email' => 'admin@unasfest.ac.id',
                'password' => Hash::make('admin123'),
                'phone' => '08123456788',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $admin->assignRole('Admin');
        }

        // Buat Juri 1 jika belum ada
        if (!User::where('email', 'juri1@unasfest.ac.id')->exists()) {
            $jury1 = User::create([
                'name' => 'Dr. Ahmad Juri',
                'email' => 'juri1@unasfest.ac.id',
                'password' => Hash::make('juri123'),
                'phone' => '08123456787',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $jury1->assignRole('Juri');
        }

        // Buat Juri 2 jika belum ada
        if (!User::where('email', 'juri2@unasfest.ac.id')->exists()) {
            $jury2 = User::create([
                'name' => 'Prof. Siti Juri',
                'email' => 'juri2@unasfest.ac.id',
                'password' => Hash::make('juri123'),
                'phone' => '08123456786',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $jury2->assignRole('Juri');
        }

        // Buat contoh peserta jika belum ada
        if (!User::where('email', 'peserta@unasfest.ac.id')->exists()) {
            $participant = User::create([
                'name' => 'Peserta Demo',
                'email' => 'peserta@unasfest.ac.id',
                'password' => Hash::make('peserta123'),
                'phone' => '08123456785',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $participant->assignRole('Peserta');
        }

        $this->command->info('User seeded successfully!');
        $this->command->info('Default accounts:');
        $this->command->info('Super Admin: superadmin@unasfest.ac.id / superadmin123');
        $this->command->info('Admin: admin@unasfest.ac.id / admin123');
        $this->command->info('Juri 1: juri1@unasfest.ac.id / juri123');
        $this->command->info('Juri 2: juri2@unasfest.ac.id / juri123');
        $this->command->info('Peserta: peserta@unasfest.ac.id / peserta123');
    }
}
