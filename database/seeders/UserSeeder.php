<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 1 Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@unasfest.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'institution' => 'UNAS Fest 2025',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('superadmin');

        // Create 5 Admin users
        for ($i = 1; $i <= 5; $i++) {
            $admin = User::create([
                'name' => "Admin User {$i}",
                'email' => "admin{$i}@unasfest.com",
                'password' => Hash::make('password123'),
                'phone' => '0812345678' . sprintf('%02d', $i),
                'institution' => 'UNAS Fest 2025',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $admin->assignRole('admin');
        }

        // Create 5 Juri users
        $juriNames = [
            'Dr. Ahmad Wijaya',
            'Prof. Siti Nurhaliza',
            'Dr. Budi Santoso',
            'Prof. Maya Sari',
            'Dr. Rizki Pratama'
        ];

        $juriInstitutions = [
            'Universitas Indonesia',
            'Institut Teknologi Bandung',
            'Universitas Gadjah Mada',
            'Institut Teknologi Sepuluh Nopember',
            'Universitas Brawijaya'
        ];

        for ($i = 1; $i <= 5; $i++) {
            $juri = User::create([
                'name' => $juriNames[$i-1],
                'email' => "juri{$i}@unasfest.com",
                'password' => Hash::make('password123'),
                'phone' => '0813456789' . sprintf('%02d', $i),
                'institution' => $juriInstitutions[$i-1],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $juri->assignRole('juri');
        }

        // Create 5 Peserta users
        $pesertaNames = [
            'Andi Pratama',
            'Sari Dewi',
            'Budi Setiawan',
            'Maya Putri',
            'Rizki Firmansyah'
        ];

        $pesertaInstitutions = [
            'SMA Negeri 1 Jakarta',
            'SMK Negeri 2 Bandung',
            'SMA Negeri 3 Surabaya',
            'SMK Negeri 4 Yogyakarta',
            'SMA Negeri 5 Medan'
        ];

        $educationLevels = ['SMA', 'SMK', 'SMA', 'SMK', 'SMA'];
        $genders = ['male', 'female', 'male', 'female', 'male'];

        for ($i = 1; $i <= 5; $i++) {
            $peserta = User::create([
                'name' => $pesertaNames[$i-1],
                'email' => "peserta{$i}@unasfest.com",
                'password' => Hash::make('password123'),
                'phone' => '0814567890' . sprintf('%02d', $i),
                'institution' => $pesertaInstitutions[$i-1],
                'gender' => $genders[$i-1],
                'student_id' => '2024' . sprintf('%04d', $i),
                'birth_date' => now()->subYears(17)->subDays(rand(1, 365)),
                'address' => "Jalan Contoh No. {$i}, Jakarta",
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '1234' . $i,
                'emergency_contact_name' => "Orang Tua {$i}",
                'emergency_contact_phone' => '0815678901' . sprintf('%02d', $i),
                'emergency_contact_relation' => 'Orang Tua',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $peserta->assignRole('peserta');
        }

        $this->command->info('Users created successfully!');
        $this->command->info('Super Admin: superadmin@unasfest.com / password123');
        $this->command->info('Admin: admin1-5@unasfest.com / password123');
        $this->command->info('Juri: juri1-5@unasfest.com / password123');
        $this->command->info('Peserta: peserta1-5@unasfest.com / password123');
    }
}