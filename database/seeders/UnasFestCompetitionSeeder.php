<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\CompetitionScoringCriteria;
use Illuminate\Support\Str;

class UnasFestCompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 main UNAS Fest competitions with new event sector categories
        $competitions = [
            [
                'name' => 'DCC (Data Challenge Competition)',
                'slug' => 'dcc',
                'category' => 'event_dcc',
                'rounds' => ['penyisihan', 'final'], // Only 2 rounds
                'scoring_criteria' => [
                    ['name' => 'Data Analysis', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Methodology', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Presentation', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Innovation', 'max_score' => 25, 'weight' => 1.0],
                ]
            ],
            [
                'name' => 'SPC (Scientific Paper Competition)',
                'slug' => 'spc',
                'category' => 'event_scientific_paper',
                'rounds' => ['penyisihan', 'semifinal', 'final'], // 3 rounds
                'scoring_criteria' => [
                    ['name' => 'Content Quality', 'max_score' => 30, 'weight' => 1.0],
                    ['name' => 'Methodology', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Writing Quality', 'max_score' => 20, 'weight' => 1.0],
                    ['name' => 'Originality', 'max_score' => 25, 'weight' => 1.0],
                ]
            ],
            [
                'name' => 'English Debate Competition',
                'slug' => 'english-debate',
                'category' => 'event_debate',
                'rounds' => ['penyisihan', 'semifinal', 'final'], // 3 rounds
                'scoring_criteria' => [
                    ['name' => 'Content', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Strategy', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Style', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Delivery', 'max_score' => 25, 'weight' => 1.0],
                ]
            ],
            [
                'name' => 'KDBI (Kompetisi Debat Bahasa Indonesia)',
                'slug' => 'kdbi',
                'category' => 'event_debate',
                'rounds' => ['penyisihan', 'semifinal', 'final'], // 3 rounds
                'scoring_criteria' => [
                    ['name' => 'Isi (Content)', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Gaya (Style)', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Strategi (Strategy)', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Penyampaian (Delivery)', 'max_score' => 25, 'weight' => 1.0],
                ]
            ],
            [
                'name' => 'Business Plan Competition',
                'slug' => 'business-plan',
                'category' => 'event_dcc',
                'rounds' => ['penyisihan', 'semifinal', 'final'], // 3 rounds
                'scoring_criteria' => [
                    ['name' => 'Business Model', 'max_score' => 30, 'weight' => 1.0],
                    ['name' => 'Market Analysis', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Financial Projection', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Presentation', 'max_score' => 20, 'weight' => 1.0],
                ]
            ],
        ];

        foreach ($competitions as $competitionData) {
            // Create or update competition
            $competition = Competition::updateOrCreate(
                ['slug' => $competitionData['slug']],
                [
                'name' => $competitionData['name'],
                'slug' => $competitionData['slug'],
                'description' => 'Kompetisi ' . $competitionData['name'] . ' dalam rangka UNAS Fest 2025',
                'category' => $competitionData['category'],
                'theme' => 'Innovation and Technology for Sustainable Future',
                'price' => 150000,
                'early_bird_price' => 100000,
                'early_bird_deadline' => now()->addDays(14),
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
                'max_participants' => 100,
                'min_team_members' => 2,
                'max_team_members' => 5,
                'requirements' => [
                    'Peserta adalah mahasiswa aktif',
                    'Setiap tim terdiri dari 2-5 orang',
                    'Satu institusi boleh mengirim lebih dari satu tim'
                ],
                'prizes' => [
                    'Juara 1: Rp 10.000.000',
                    'Juara 2: Rp 7.500.000',
                    'Juara 3: Rp 5.000.000'
                ],
                'rules' => [
                    'Peserta wajib mengikuti seluruh rangkaian kompetisi',
                    'Tidak diperbolehkan mengganti anggota tim setelah registrasi',
                    'Keputusan juri tidak dapat diganggu gugat'
                ],
                'is_active' => true,
                'status' => 'active',
                'is_team_competition' => true,
                'allow_individual' => false,
                'prize_amount' => 22500000,
                'type' => 'team',
                'short_description' => 'Kompetisi ' . $competitionData['name'] . ' tingkat nasional',
                'contact_person' => 'Panitia UNAS Fest 2025',
                'contact_email' => 'info@unasfest.com',
                'contact_phone' => '081234567890',
                'whatsapp_group_link' => 'https://chat.whatsapp.com/unasfest2025',
                'terms_conditions' => 'Syarat dan ketentuan berlaku sesuai panduan kompetisi',
                'judging_criteria' => $competitionData['scoring_criteria'],
                'is_featured' => true,
                'show_leaderboard' => true,
                ]
            );

            // Delete existing rounds and criteria
            $competition->rounds()->delete();
            $competition->scoringCriteria()->delete();

            // Create rounds for each competition
            foreach ($competitionData['rounds'] as $index => $roundType) {
                CompetitionRound::create([
                    'competition_id' => $competition->id,
                    'round_type' => $roundType,
                    'name' => ucfirst($roundType),
                    'description' => 'Babak ' . ucfirst($roundType) . ' kompetisi ' . $competition->name,
                    'round_number' => 1,
                    'start_date' => now()->addDays(35 + ($index * 5)),
                    'end_date' => now()->addDays(36 + ($index * 5)),
                    'status' => 'upcoming',
                    'is_active' => true,
                ]);
            }

            // Create scoring criteria for each competition
            foreach ($competitionData['scoring_criteria'] as $index => $criteria) {
                CompetitionScoringCriteria::create([
                    'competition_id' => $competition->id,
                    'criteria_name' => $criteria['name'],
                    'description' => 'Kriteria penilaian ' . $criteria['name'],
                    'max_score' => $criteria['max_score'],
                    'weight' => $criteria['weight'],
                    'order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('UNAS Fest competitions created successfully!');
        $this->command->info('Created 5 competitions with their rounds and scoring criteria.');
    }
}
