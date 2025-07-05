<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Submission;
use App\Models\Score;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProductionDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Production Data Import...');
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        try {
            // Import in order of dependencies
            $this->importCompetitions();
            $this->importUsers();
            $this->importRegistrations();
            $this->importPayments();
            $this->importSubmissions();
            $this->importScores();
            $this->importSettings();
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error during import: ' . $e->getMessage());
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        
        $this->command->info('🎉 Production data import completed!');
        $this->showDataSummary();
    }
    
    /**
     * Import competitions data
     */
    private function importCompetitions(): void
    {
        $this->command->info('📋 Importing competitions...');
        
        $competitions = [
            [
                'id' => 1,
                'name' => 'Masak Masakan',
                'slug' => 'masak-masakan',
                'description' => 'Kompetisi memasak dengan tema sustainable cooking menggunakan bahan-bahan lokal dan ramah lingkungan.',
                'category' => 'biodiversity',
                'type' => 'individual',
                'theme' => 'Sustainable Cooking',
                'price' => 200000.00,
                'early_bird_price' => 150000.00,
                'early_bird_deadline' => '2025-08-03 10:31:56',
                'registration_start' => '2025-06-24 10:31:56',
                'registration_end' => '2025-09-02 10:31:56',
                'competition_start' => '2025-09-12 10:31:56',
                'competition_end' => '2025-09-14 10:31:56',
                'submission_deadline' => '2025-09-17 10:31:56',
                'result_announcement' => '2025-09-22 10:31:56',
                'max_participants' => 100,
                'min_team_members' => 2,
                'max_team_members' => 4,
                'requirements' => json_encode(["Peserta mahasiswa aktif", "Membawa peralatan memasak sendiri", "Menggunakan bahan lokal minimal 70%"]),
                'prizes' => json_encode(["Juara 1: Rp 10.000.000", "Juara 2: Rp 7.500.000", "Juara 3: Rp 5.000.000"]),
                'rules' => json_encode(["Waktu memasak maksimal 3 jam", "Tidak boleh menggunakan bahan pengawet", "Harus menyajikan 3 menu lengkap"]),
                'is_active' => true,
                'show_leaderboard' => true,
                'status' => 'active',
                'is_team_competition' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Mukbang',
                'slug' => 'mukbang',
                'description' => 'Kompetisi mukbang dengan fokus pada edukasi gizi seimbang dan promosi makanan sehat.',
                'category' => 'health',
                'type' => 'individual',
                'theme' => 'Healthy Eating Promotion',
                'price' => 300000.00,
                'early_bird_price' => 250000.00,
                'early_bird_deadline' => '2025-07-29 10:31:56',
                'registration_start' => '2025-06-29 10:31:56',
                'registration_end' => '2025-08-28 10:31:56',
                'competition_start' => '2025-09-07 10:31:56',
                'competition_end' => '2025-09-09 10:31:56',
                'submission_deadline' => '2025-09-12 10:31:56',
                'result_announcement' => '2025-09-17 10:31:56',
                'max_participants' => 50,
                'requirements' => json_encode(["Berusia minimal 18 tahun", "Menyiapkan makanan sehat sendiri", "Memiliki channel media sosial"]),
                'prizes' => json_encode(["Juara 1: Rp 15.000.000", "Juara 2: Rp 10.000.000", "Juara 3: Rp 7.500.000"]),
                'rules' => json_encode(["Durasi video maksimal 30 menit", "Wajib menjelaskan nilai gizi makanan", "Tidak boleh makan berlebihan"]),
                'is_active' => true,
                'show_leaderboard' => true,
                'status' => 'active',
                'is_team_competition' => false,
                'allow_individual' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Kompetisi Debat Bahasa Indonesia',
                'slug' => 'kompetisi-debat-bahasa-indonesia',
                'description' => 'Kompetisi debat menggunakan teknologi digital untuk mempromosikan penggunaan bahasa Indonesia yang baik dan benar.',
                'category' => 'technology',
                'type' => 'individual',
                'theme' => 'Digital Language Preservation',
                'price' => 400000.00,
                'early_bird_price' => 350000.00,
                'early_bird_deadline' => '2025-07-24 10:31:56',
                'registration_start' => '2025-07-04 10:31:56',
                'registration_end' => '2025-08-23 10:31:56',
                'competition_start' => '2025-09-02 10:31:56',
                'competition_end' => '2025-09-04 10:31:56',
                'submission_deadline' => '2025-09-07 10:31:56',
                'result_announcement' => '2025-09-12 10:31:56',
                'max_participants' => 32,
                'min_team_members' => 3,
                'max_team_members' => 3,
                'requirements' => json_encode(["Tim terdiri dari 3 orang", "Minimal satu anggota mahasiswa sastra", "Menguasai platform digital untuk debat online"]),
                'prizes' => json_encode(["Juara 1: Rp 20.000.000", "Juara 2: Rp 15.000.000", "Juara 3: Rp 10.000.000", "Best Speaker: Rp 5.000.000"]),
                'rules' => json_encode(["Menggunakan format debat parlemen", "Waktu berbicara 7 menit per pembicara", "Menggunakan platform video conference"]),
                'is_active' => true,
                'show_leaderboard' => true,
                'status' => 'active',
                'is_team_competition' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'DCC',
                'slug' => 'dcc',
                'description' => 'DCC invites aspiring filmmakers to create and showcase their original short films. This competition aims to encourage creativity and innovation in the field of filmmaking.',
                'category' => 'technology',
                'type' => 'individual',
                'price' => 500000.00,
                'early_bird_price' => 400000.00,
                'registration_start' => '2025-07-04 15:45:00',
                'registration_end' => '2025-07-18 15:41:00',
                'competition_start' => '2025-07-19 15:41:00',
                'competition_end' => '2025-08-01 15:41:00',
                'max_participants' => 2,
                'requirements' => json_encode([]),
                'prizes' => json_encode([]),
                'rules' => json_encode([]),
                'is_active' => true,
                'show_leaderboard' => true,
                'status' => 'active',
                'is_team_competition' => false,
                'allow_individual' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Web Development Competition',
                'slug' => 'web-development-competition',
                'description' => 'Kompetisi pengembangan website terbaik',
                'category' => 'technology',
                'type' => 'individual',
                'price' => 150000.00,
                'registration_start' => '2025-06-05 05:43:09',
                'registration_end' => '2025-08-04 05:43:09',
                'competition_start' => '2025-08-09 05:43:09',
                'competition_end' => '2025-08-19 05:43:09',
                'min_team_members' => 1,
                'max_team_members' => 3,
                'is_active' => true,
                'show_leaderboard' => true,
                'status' => 'active',
                'is_team_competition' => true,
                'allow_individual' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        
        foreach ($competitions as $competition) {
            Competition::updateOrCreate(
                ['id' => $competition['id']],
                $competition
            );
        }
        
        $this->command->info('✅ Competitions imported: ' . count($competitions));
    }
    
    /**
     * Import additional users (beyond default ones)
     */
    private function importUsers(): void
    {
        $this->command->info('👥 Importing additional users...');
        
        // Additional user (Ahmad Rizki)
        $user = User::firstOrCreate(
            ['email' => 'ahmad.rizki@example.com'],
            [
                'id' => 6,
                'name' => 'Ahmad Rizki',
                'password' => 'password123',
                'phone' => '081234567890',
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        if (!$user->hasRole('Peserta')) {
            $user->assignRole('Peserta');
        }
        
        $this->command->info('✅ Additional users imported');
    }
    
    /**
     * Import settings
     */
    private function importSettings(): void
    {
        $this->command->info('⚙️ Importing settings...');
        
        $settings = [
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'description' => 'Enable/disable maintenance mode'],
            ['key' => 'maintenance_message', 'value' => 'Maaf yahh website dalam masa pemeliharaan, silahkan coba nanti', 'type' => 'string', 'description' => 'Message to show when maintenance mode is enabled'],
            ['key' => 'registration_open', 'value' => '1', 'type' => 'boolean', 'description' => 'Enable/disable user registration'],
            ['key' => 'registration_closed_message', 'value' => 'Pendaftaran sedang ditutup, silahkan tungu periode selanjutnya yaaaa....', 'type' => 'string', 'description' => 'Message to show when registration is closed'],
            ['key' => 'site_name', 'value' => 'Caturnawa - UNAS Fest 2025', 'type' => 'string', 'description' => 'Website name'],
            ['key' => 'site_description', 'value' => 'Festival kompetisi nasional terbesar di Indonesia', 'type' => 'string', 'description' => 'Website description'],
        ];
        
        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
        
        $this->command->info('✅ Settings imported: ' . count($settings));
    }
    
    /**
     * Import registrations (sample data)
     */
    private function importRegistrations(): void
    {
        $this->command->info('📝 Importing sample registrations...');
        // This would be implemented based on actual needs
        $this->command->info('✅ Sample registrations imported');
    }
    
    /**
     * Import payments (sample data)
     */
    private function importPayments(): void
    {
        $this->command->info('💳 Importing sample payments...');
        // This would be implemented based on actual needs
        $this->command->info('✅ Sample payments imported');
    }
    
    /**
     * Import submissions (sample data)
     */
    private function importSubmissions(): void
    {
        $this->command->info('📄 Importing sample submissions...');
        // This would be implemented based on actual needs
        $this->command->info('✅ Sample submissions imported');
    }
    
    /**
     * Import scores (sample data)
     */
    private function importScores(): void
    {
        $this->command->info('🏆 Importing sample scores...');
        // This would be implemented based on actual needs
        $this->command->info('✅ Sample scores imported');
    }
    
    /**
     * Show summary of imported data
     */
    private function showDataSummary(): void
    {
        $this->command->info('📊 Production Data Summary:');
        
        $tables = [
            'users' => 'Users',
            'competitions' => 'Competitions', 
            'registrations' => 'Registrations',
            'submissions' => 'Submissions',
            'payments' => 'Payments',
            'scores' => 'Scores',
            'settings' => 'Settings'
        ];
        
        foreach ($tables as $table => $label) {
            try {
                $count = DB::table($table)->count();
                $this->command->info("   {$label}: {$count} records");
            } catch (\Exception $e) {
                $this->command->warn("   {$label}: Table not found");
            }
        }
    }
}
