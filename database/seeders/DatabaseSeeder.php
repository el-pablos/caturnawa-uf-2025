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
        // Jalankan role permission seeder terlebih dahulu
        $this->call(RolePermissionSeeder::class);

        // Buat Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@unasfest.ac.id',
            'password' => 'superadmin123',
            'phone' => '08123456789',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('Super Admin');

        // Buat Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@unasfest.ac.id',
            'password' => 'admin123',
            'phone' => '08123456788',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Admin');

        // Buat Juri
        $jury1 = User::create([
            'name' => 'Dr. Ahmad Juri',
            'email' => 'juri1@unasfest.ac.id',
            'password' => 'juri123',
            'phone' => '08123456787',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $jury1->assignRole('Juri');

        $jury2 = User::create([
            'name' => 'Prof. Siti Juri',
            'email' => 'juri2@unasfest.ac.id',
            'password' => 'juri123',
            'phone' => '08123456786',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $jury2->assignRole('Juri');

        // Buat contoh peserta
        $participant = User::create([
            'name' => 'Peserta Demo',
            'email' => 'peserta@unasfest.ac.id',
            'password' => 'peserta123',
            'phone' => '08123456785',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $participant->assignRole('Peserta');

        // Buat contoh kompetisi
        $competitions = [
            [
                'name' => 'Masak Masakan',
                'slug' => 'masak-masakan',
                'description' => 'Kompetisi memasak dengan tema sustainable cooking menggunakan bahan-bahan lokal dan ramah lingkungan.',
                'category' => 'biodiversity',
                'theme' => 'Sustainable Cooking',
                'price' => 200000,
                'early_bird_price' => 150000,
                'early_bird_deadline' => now()->addDays(30),
                'registration_start' => now()->subDays(10),
                'registration_end' => now()->addDays(60),
                'competition_start' => now()->addDays(70),
                'competition_end' => now()->addDays(72),
                'submission_deadline' => now()->addDays(75),
                'result_announcement' => now()->addDays(80),
                'max_participants' => 100,
                'min_team_members' => 2,
                'max_team_members' => 4,
                'is_team_competition' => true,
                'allow_individual' => false,
                'requirements' => [
                    'Peserta mahasiswa aktif',
                    'Membawa peralatan memasak sendiri',
                    'Menggunakan bahan lokal minimal 70%'
                ],
                'prizes' => [
                    'Juara 1: Rp 10.000.000',
                    'Juara 2: Rp 7.500.000',
                    'Juara 3: Rp 5.000.000'
                ],
                'rules' => [
                    'Waktu memasak maksimal 3 jam',
                    'Tidak boleh menggunakan bahan pengawet',
                    'Harus menyajikan 3 menu lengkap'
                ]
            ],
            [
                'name' => 'Mukbang',
                'slug' => 'mukbang',
                'description' => 'Kompetisi mukbang dengan fokus pada edukasi gizi seimbang dan promosi makanan sehat.',
                'category' => 'health',
                'theme' => 'Healthy Eating Promotion',
                'price' => 300000,
                'early_bird_price' => 250000,
                'early_bird_deadline' => now()->addDays(25),
                'registration_start' => now()->subDays(5),
                'registration_end' => now()->addDays(55),
                'competition_start' => now()->addDays(65),
                'competition_end' => now()->addDays(67),
                'submission_deadline' => now()->addDays(70),
                'result_announcement' => now()->addDays(75),
                'max_participants' => 50,
                'is_team_competition' => false,
                'allow_individual' => true,
                'requirements' => [
                    'Berusia minimal 18 tahun',
                    'Menyiapkan makanan sehat sendiri',
                    'Memiliki channel media sosial'
                ],
                'prizes' => [
                    'Juara 1: Rp 15.000.000',
                    'Juara 2: Rp 10.000.000',
                    'Juara 3: Rp 7.500.000'
                ],
                'rules' => [
                    'Durasi video maksimal 30 menit',
                    'Wajib menjelaskan nilai gizi makanan',
                    'Tidak boleh makan berlebihan'
                ]
            ],
            [
                'name' => 'Kompetisi Debat Bahasa Indonesia',
                'slug' => 'kompetisi-debat-bahasa-indonesia',
                'description' => 'Kompetisi debat menggunakan teknologi digital untuk mempromosikan penggunaan bahasa Indonesia yang baik dan benar.',
                'category' => 'technology',
                'theme' => 'Digital Language Preservation',
                'price' => 400000,
                'early_bird_price' => 350000,
                'early_bird_deadline' => now()->addDays(20),
                'registration_start' => now(),
                'registration_end' => now()->addDays(50),
                'competition_start' => now()->addDays(60),
                'competition_end' => now()->addDays(62),
                'submission_deadline' => now()->addDays(65),
                'result_announcement' => now()->addDays(70),
                'max_participants' => 32,
                'min_team_members' => 3,
                'max_team_members' => 3,
                'is_team_competition' => true,
                'allow_individual' => false,
                'requirements' => [
                    'Tim terdiri dari 3 orang',
                    'Minimal satu anggota mahasiswa sastra',
                    'Menguasai platform digital untuk debat online'
                ],
                'prizes' => [
                    'Juara 1: Rp 20.000.000',
                    'Juara 2: Rp 15.000.000',
                    'Juara 3: Rp 10.000.000',
                    'Best Speaker: Rp 5.000.000'
                ],
                'rules' => [
                    'Menggunakan format debat parlemen',
                    'Waktu berbicara 7 menit per pembicara',
                    'Menggunakan platform video conference'
                ]
            ]
        ];

        foreach ($competitions as $competitionData) {
            Competition::create($competitionData);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Default accounts:');
        $this->command->info('Super Admin: superadmin@unasfest.ac.id / superadmin123');
        $this->command->info('Admin: admin@unasfest.ac.id / admin123');
        $this->command->info('Juri: juri1@unasfest.ac.id / juri123');
        $this->command->info('Peserta: peserta@unasfest.ac.id / peserta123');
    }
}
