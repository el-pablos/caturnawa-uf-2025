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
                'name' => 'Scientific Paper Competition',
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
                'name' => 'Short Movie Competition',
                'slug' => 'short-movie',
                'category' => 'event_dcc',
                'rounds' => ['penyisihan', 'semifinal', 'final'], // 3 rounds
                'scoring_criteria' => [
                    ['name' => 'Story & Script', 'max_score' => 30, 'weight' => 1.0],
                    ['name' => 'Cinematography', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Editing & Post-Production', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Overall Impact', 'max_score' => 20, 'weight' => 1.0],
                ]
            ],
            [
                'name' => 'Photography Competition',
                'slug' => 'photography',
                'category' => 'event_dcc',
                'rounds' => ['penyisihan', 'semifinal', 'final'], // 3 rounds
                'scoring_criteria' => [
                    ['name' => 'Composition & Technique', 'max_score' => 30, 'weight' => 1.0],
                    ['name' => 'Creativity & Originality', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Visual Impact', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Theme Interpretation', 'max_score' => 20, 'weight' => 1.0],
                ]
            ],
        ];

        // Clear all existing competitions first
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Competition::query()->delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($competitions as $competitionData) {
            // Set specific settings for photography competition
            $isPhotography = $competitionData['slug'] === 'photography';

            echo "Creating competition: " . $competitionData['name'] . " (" . $competitionData['slug'] . ")\n";

            // Create new competition
            $competition = Competition::create([
                'name' => $competitionData['name'],
                'slug' => $competitionData['slug'],
                'description' => $isPhotography
                    ? 'Kompetisi fotografi yang menantang kreativitas dan teknik fotografi peserta dalam menginterpretasikan tema yang diberikan. Peserta dapat berpartisipasi secara individu atau tim untuk menciptakan karya fotografi yang memukau dan bermakna.'
                    : 'Kompetisi ' . $competitionData['name'] . ' dalam rangka UNAS Fest 2025',
                'category' => $competitionData['category'],
                'theme' => $isPhotography
                    ? 'Capturing Moments, Creating Stories'
                    : 'Innovation and Technology for Sustainable Future',
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
                'max_participants' => $isPhotography ? 150 : 100,
                'min_team_members' => $isPhotography ? 1 : 2,
                'max_team_members' => $isPhotography ? 3 : 5,
                'requirements' => $isPhotography ? [
                    'Peserta adalah siswa SMA/SMK atau mahasiswa aktif',
                    'Dapat berpartisipasi secara individu atau tim (maksimal 3 orang)',
                    'Foto harus karya asli peserta',
                    'Tidak diperbolehkan menggunakan foto yang pernah dipublikasikan',
                    'Format file: JPEG/JPG dengan resolusi minimal 300 DPI'
                ] : [
                    'Peserta adalah mahasiswa aktif',
                    'Setiap tim terdiri dari 2-5 orang',
                    'Satu institusi boleh mengirim lebih dari satu tim'
                ],
                'prizes' => $isPhotography ? [
                    'Juara 1: Rp 7.500.000 + Kamera DSLR',
                    'Juara 2: Rp 5.000.000 + Lensa Kamera',
                    'Juara 3: Rp 3.000.000 + Tripod Professional',
                    'Best Composition: Rp 1.000.000',
                    'People\'s Choice: Rp 1.000.000'
                ] : [
                    'Juara 1: Rp 10.000.000',
                    'Juara 2: Rp 7.500.000',
                    'Juara 3: Rp 5.000.000'
                ],
                'rules' => $isPhotography ? [
                    'Peserta wajib mengikuti seluruh rangkaian kompetisi',
                    'Foto yang disubmit harus sesuai dengan tema yang diberikan',
                    'Dilarang melakukan manipulasi digital yang berlebihan',
                    'Peserta boleh menggunakan filter dan editing dasar',
                    'Keputusan juri tidak dapat diganggu gugat'
                ] : [
                    'Peserta wajib mengikuti seluruh rangkaian kompetisi',
                    'Tidak diperbolehkan mengganti anggota tim setelah registrasi',
                    'Keputusan juri tidak dapat diganggu gugat'
                ],
                'is_active' => true,
                'status' => 'active',
                'is_team_competition' => $isPhotography ? true : true,
                'allow_individual' => $isPhotography ? true : false,
                'prize_amount' => $isPhotography ? 17500000 : 22500000,
                'type' => $isPhotography ? 'individual_or_team' : 'team',
                'short_description' => $isPhotography
                    ? 'Kompetisi fotografi tingkat nasional untuk menangkap momen dan menciptakan cerita melalui lensa'
                    : 'Kompetisi ' . $competitionData['name'] . ' tingkat nasional',
                'contact_person' => 'Panitia UNAS Fest 2025',
                'contact_email' => 'info@unasfest.com',
                'contact_phone' => '081234567890',
                'whatsapp_group_link' => 'https://chat.whatsapp.com/unasfest2025',
                'terms_conditions' => 'Syarat dan ketentuan berlaku sesuai panduan kompetisi',
                'judging_criteria' => $competitionData['scoring_criteria'],
                'is_featured' => true,
                'show_leaderboard' => true,
            ]);

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
