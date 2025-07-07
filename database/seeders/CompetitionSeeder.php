<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Competition;
use Illuminate\Support\Str;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create team competition for testing
        Competition::create([
            'name' => 'Kompetisi Tim Test',
            'slug' => 'kompetisi-tim-test',
            'description' => 'Kompetisi tim untuk testing form registrasi dengan anggota tim yang fleksibel.',
            'category' => 'event_dcc',
            'theme' => 'Innovation in Technology',
            'price' => 100000,
            'early_bird_price' => 75000,
            'early_bird_deadline' => now()->addDays(7),
            'registration_start' => now()->subDays(1),
            'registration_end' => now()->addDays(30),
            'registration_deadline' => now()->addDays(30),
            'round1_date' => now()->addDays(35),
            'semifinal_date' => now()->addDays(40),
            'final_date' => now()->addDays(45),
            'competition_start' => now()->addDays(35),
            'competition_end' => now()->addDays(45),
            'submission_deadline' => now()->addDays(32),
            'result_announcement' => now()->addDays(50),
            'max_participants' => 50,
            'min_team_members' => 1,
            'max_team_members' => 5,
            'requirements' => [
                'Peserta adalah siswa SMA/SMK atau mahasiswa',
                'Setiap tim maksimal 5 orang',
                'Minimal 1 orang per tim'
            ],
            'prizes' => [
                'Juara 1: Rp 5.000.000',
                'Juara 2: Rp 3.000.000',
                'Juara 3: Rp 2.000.000'
            ],
            'rules' => [
                'Peserta wajib mengikuti seluruh rangkaian kompetisi',
                'Tidak diperbolehkan mengganti anggota tim setelah registrasi',
                'Keputusan juri tidak dapat diganggu gugat'
            ],
            'is_active' => true,
            'status' => 'active',
            'is_team_competition' => true,
            'allow_individual' => true,
            'prize_amount' => 10000000,
            'type' => 'team',
            'short_description' => 'Kompetisi tim untuk testing form registrasi',
            'contact_person' => 'Admin Test',
            'contact_email' => 'admin@unasfest.com',
            'contact_phone' => '081234567890',
            'whatsapp_group_link' => 'https://chat.whatsapp.com/test',
            'terms_conditions' => 'Syarat dan ketentuan berlaku',
            'judging_criteria' => [
                'Inovasi: 30%',
                'Implementasi: 30%',
                'Presentasi: 25%',
                'Dampak: 15%'
            ],
            'is_featured' => true,
            'show_leaderboard' => true,
        ]);

        $this->command->info('Competition seeder completed successfully!');
    }
}
