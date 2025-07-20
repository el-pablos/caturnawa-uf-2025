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
        // Create UNAS Fest 2025 competitions with 3 main events
        $competitions = [
            // Event 1: Debate Competition
            [
                'name' => 'KDBI (Kompetisi Debat Bahasa Indonesia)',
                'slug' => 'kdbi',
                'category' => 'debate_competition',
                'event_name' => 'Debate Competition',
                'rounds' => ['penyisihan', 'semifinal', 'final'],
                'scoring_criteria' => [
                    ['name' => 'Isi (Content)', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Gaya (Style)', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Strategi (Strategy)', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Penyampaian (Delivery)', 'max_score' => 25, 'weight' => 1.0],
                ]
            ],
            [
                'name' => 'EDC (English Debate Competition)',
                'slug' => 'edc',
                'category' => 'debate_competition',
                'event_name' => 'Debate Competition',
                'rounds' => ['penyisihan', 'semifinal', 'final'],
                'scoring_criteria' => [
                    ['name' => 'Content', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Strategy', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Style', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Delivery', 'max_score' => 25, 'weight' => 1.0],
                ]
            ],

            // Event 2: DCC (Digital Contest Competition)
            [
                'name' => 'Short Movie Competition',
                'slug' => 'short-movie',
                'category' => 'dcc',
                'event_name' => 'DCC (Digital Contest Competition)',
                'rounds' => ['penyisihan', 'semifinal', 'final'],
                'scoring_criteria' => [
                    ['name' => 'Story & Script', 'max_score' => 30, 'weight' => 1.0],
                    ['name' => 'Cinematography', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Editing & Post-Production', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Overall Impact', 'max_score' => 20, 'weight' => 1.0],
                ]
            ],
            [
                'name' => 'Fotografi',
                'slug' => 'fotografi',
                'category' => 'dcc',
                'event_name' => 'DCC (Digital Contest Competition)',
                'rounds' => ['penyisihan', 'semifinal', 'final'],
                'scoring_criteria' => [
                    ['name' => 'Komposisi & Teknik', 'max_score' => 30, 'weight' => 1.0],
                    ['name' => 'Kreativitas & Originalitas', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Dampak Visual', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Interpretasi Tema', 'max_score' => 20, 'weight' => 1.0],
                ]
            ],

            // Event 3: SPC (Scientific Paper Competition)
            [
                'name' => 'Karya Ilmiah',
                'slug' => 'karya-ilmiah',
                'category' => 'spc',
                'event_name' => 'SPC (Scientific Paper Competition)',
                'rounds' => ['penyisihan', 'semifinal', 'final'],
                'scoring_criteria' => [
                    ['name' => 'Kualitas Konten', 'max_score' => 30, 'weight' => 1.0],
                    ['name' => 'Metodologi', 'max_score' => 25, 'weight' => 1.0],
                    ['name' => 'Kualitas Penulisan', 'max_score' => 20, 'weight' => 1.0],
                    ['name' => 'Originalitas', 'max_score' => 25, 'weight' => 1.0],
                ]
            ],
        ];

        // Clear all existing competitions first
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Competition::query()->delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($competitions as $competitionData) {
            echo "Creating competition: " . $competitionData['name'] . " (" . $competitionData['slug'] . ")\n";

            // Set specific settings based on competition type
            $isFotografi = $competitionData['slug'] === 'fotografi';
            $isDebate = in_array($competitionData['category'], ['debate_competition']);
            $isSPC = $competitionData['category'] === 'spc';

            // Create new competition
            $competition = Competition::create([
                'name' => $competitionData['name'],
                'slug' => $competitionData['slug'],
                'description' => $this->getCompetitionDescription($competitionData),
                'category' => $competitionData['category'],
                'theme' => $this->getCompetitionTheme($competitionData),
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
                'max_participants' => $isFotografi ? 150 : 100,
                'min_team_members' => $isFotografi ? 1 : ($isDebate ? 3 : 2),
                'max_team_members' => $isFotografi ? 3 : ($isDebate ? 5 : 5),
                'requirements' => $this->getCompetitionRequirements($competitionData),
                'prizes' => $this->getCompetitionPrizes($competitionData),
                'rules' => $this->getCompetitionRules($competitionData),
                'is_active' => true,
                'status' => 'active',
                'is_team_competition' => true,
                'allow_individual' => $isFotografi,
                'prize_amount' => 22500000,
                'type' => $isFotografi ? 'individual' : 'team',
                'short_description' => $this->getCompetitionShortDescription($competitionData),
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
        $this->command->info('Created 5 competitions across 3 events with their rounds and scoring criteria.');
    }

    private function getCompetitionDescription($competitionData)
    {
        $descriptions = [
            'kdbi' => 'Kompetisi Debat Bahasa Indonesia (KDBI) adalah ajang bergengsi untuk menguji kemampuan berargumentasi dan berpikir kritis dalam bahasa Indonesia.',
            'edc' => 'English Debate Competition (EDC) adalah kompetisi debat internasional yang menantang kemampuan berbahasa Inggris dan argumentasi peserta.',
            'short-movie' => 'Kompetisi film pendek yang menantang kreativitas dalam bercerita melalui medium audiovisual dengan durasi maksimal 10 menit.',
            'fotografi' => 'Kompetisi fotografi yang menantang kreativitas dan teknik fotografi peserta dalam menginterpretasikan tema yang diberikan.',
            'karya-ilmiah' => 'Kompetisi karya ilmiah yang mendorong inovasi dan penelitian mahasiswa dalam berbagai bidang keilmuan.'
        ];

        return $descriptions[$competitionData['slug']] ?? 'Kompetisi ' . $competitionData['name'] . ' dalam rangka UNAS Fest 2025';
    }

    private function getCompetitionTheme($competitionData)
    {
        $themes = [
            'kdbi' => 'Membangun Bangsa Melalui Dialog Konstruktif',
            'edc' => 'Global Challenges, Local Solutions',
            'short-movie' => 'Stories That Matter',
            'fotografi' => 'Capturing Moments, Creating Stories',
            'karya-ilmiah' => 'Innovation for Sustainable Future'
        ];

        return $themes[$competitionData['slug']] ?? 'Innovation and Technology for Sustainable Future';
    }

    private function getCompetitionRequirements($competitionData)
    {
        $requirements = [
            'kdbi' => [
                'Peserta adalah mahasiswa aktif',
                'Setiap tim terdiri dari 3-5 orang',
                'Menguasai teknik debat bahasa Indonesia',
                'Memiliki kemampuan analisis dan argumentasi yang baik'
            ],
            'edc' => [
                'Peserta adalah mahasiswa aktif',
                'Setiap tim terdiri dari 3-5 orang',
                'Menguasai bahasa Inggris dengan baik',
                'Memiliki pengalaman debat (diutamakan)'
            ],
            'short-movie' => [
                'Peserta adalah mahasiswa aktif',
                'Setiap tim terdiri dari 2-5 orang',
                'Memiliki pengalaman dalam produksi video',
                'Durasi film maksimal 10 menit'
            ],
            'fotografi' => [
                'Peserta adalah siswa SMA/SMK atau mahasiswa aktif',
                'Dapat berpartisipasi secara individu atau tim (maksimal 3 orang)',
                'Foto harus karya asli peserta',
                'Format file: JPEG/JPG dengan resolusi minimal 300 DPI'
            ],
            'karya-ilmiah' => [
                'Peserta adalah mahasiswa aktif',
                'Setiap tim terdiri dari 2-5 orang',
                'Karya ilmiah harus original dan belum dipublikasikan',
                'Sesuai dengan kaidah penulisan ilmiah'
            ]
        ];

        return $requirements[$competitionData['slug']] ?? [
            'Peserta adalah mahasiswa aktif',
            'Setiap tim terdiri dari 2-5 orang',
            'Satu institusi boleh mengirim lebih dari satu tim'
        ];
    }

    private function getCompetitionPrizes($competitionData)
    {
        return [
            'Juara 1: Rp 10.000.000',
            'Juara 2: Rp 7.500.000',
            'Juara 3: Rp 5.000.000'
        ];
    }

    private function getCompetitionRules($competitionData)
    {
        return [
            'Peserta wajib mengikuti seluruh rangkaian kompetisi',
            'Tidak diperbolehkan mengganti anggota tim setelah registrasi',
            'Keputusan juri tidak dapat diganggu gugat',
            'Peserta wajib mematuhi kode etik kompetisi'
        ];
    }

    private function getCompetitionShortDescription($competitionData)
    {
        $descriptions = [
            'kdbi' => 'Kompetisi debat bahasa Indonesia tingkat nasional',
            'edc' => 'Kompetisi debat bahasa Inggris tingkat nasional',
            'short-movie' => 'Kompetisi film pendek tingkat nasional',
            'fotografi' => 'Kompetisi fotografi tingkat nasional',
            'karya-ilmiah' => 'Kompetisi karya ilmiah tingkat nasional'
        ];

        return $descriptions[$competitionData['slug']] ?? 'Kompetisi ' . $competitionData['name'] . ' tingkat nasional';
    }
}
