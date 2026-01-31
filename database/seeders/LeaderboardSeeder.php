<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\Registration;
use App\Models\Score;
use App\Models\LeaderboardEntry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Seeder untuk leaderboard dan scores
 */
class LeaderboardSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏆 Creating leaderboard and scores data...');

        // Get all competitions
        $competitions = Competition::all();

        if ($competitions->isEmpty()) {
            $this->command->warn('   ⚠ No competitions found. Skipping leaderboard seeding.');
            return;
        }

        // Create a jury user if not exists
        $jury = User::where('email', 'juri@unasfest.com')->first();
        if (!$jury) {
            $jury = User::create([
                'name' => 'Dr. Juri Utama',
                'email' => 'juri@unasfest.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'phone' => '081234567890',
                'institution' => 'Universitas Nasional',
            ]);
            $jury->assignRole('juri');
        }

        // Create additional jury members
        $juries = [$jury];
        $juryNames = [
            ['Dr. Ahmad Wijaya', 'juri2@unasfest.com'],
            ['Prof. Siti Rahayu', 'juri3@unasfest.com'],
            ['Dr. Budi Santoso', 'juri4@unasfest.com'],
        ];

        foreach ($juryNames as $juryData) {
            $existingJury = User::where('email', $juryData[1])->first();
            if (!$existingJury) {
                $newJury = User::create([
                    'name' => $juryData[0],
                    'email' => $juryData[1],
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now(),
                    'phone' => '08' . rand(1, 9) . rand(10000000, 99999999),
                    'institution' => 'Universitas Nasional',
                ]);
                $newJury->assignRole('juri');
                $juries[] = $newJury;
            } else {
                $juries[] = $existingJury;
            }
        }

        $totalScores = 0;
        $totalLeaderboard = 0;

        foreach ($competitions as $competition) {
            // Get paid registrations for this competition
            $registrations = Registration::where('competition_id', $competition->id)
                ->where('status', 'paid')
                ->get();

            if ($registrations->isEmpty()) {
                continue;
            }

            $this->command->info("   📊 Processing {$competition->name}...");

            // Create scores for each registration (from multiple juries)
            $registrationScores = [];
            
            foreach ($registrations as $registration) {
                $scores = [];
                
                // Each jury gives a score
                foreach ($juries as $juryUser) {
                    $criteriaScores = $this->generateCriteriaScores($competition->category);
                    $totalScore = array_sum(array_column($criteriaScores, 'score'));
                    
                    // Check if score already exists
                    $existingScore = Score::where('competition_id', $competition->id)
                        ->where('registration_id', $registration->id)
                        ->where('jury_id', $juryUser->id)
                        ->first();

                    if (!$existingScore) {
                        Score::create([
                            'competition_id' => $competition->id,
                            'registration_id' => $registration->id,
                            'jury_id' => $juryUser->id,
                            'criteria_scores' => json_encode($criteriaScores),
                            'total_score' => $totalScore,
                            'comments' => $this->generateJuryComment($totalScore),
                            'feedback' => $this->generateFeedback($competition->category),
                            'is_final' => true,
                            'submitted_at' => now()->subDays(rand(1, 14)),
                        ]);
                        $totalScores++;
                    }
                    
                    $scores[] = $totalScore;
                }

                // Calculate average score
                $avgScore = count($scores) > 0 ? array_sum($scores) / count($scores) : 0;
                $registrationScores[$registration->id] = [
                    'registration' => $registration,
                    'avg_score' => $avgScore,
                ];
            }

            // Sort by average score first to determine placement
            uasort($registrationScores, fn($a, $b) => $b['avg_score'] <=> $a['avg_score']);

            $rank = 1;
            $totalParticipants = count($registrationScores);
            
            foreach ($registrationScores as $regId => $data) {
                $registration = $data['registration'];
                $avgScore = $data['avg_score'];

                // Calculate victory points based on tournament-style system
                // Top 3 get bonus VP, others get VP based on performance
                $victoryPoints = $this->calculateVictoryPoints($rank, $totalParticipants, $avgScore);

                // Determine rank type
                $rankType = $rank <= 10 ? 'position' : 'mention';

                // Check if leaderboard entry exists
                $existingEntry = LeaderboardEntry::where('competition_id', $competition->id)
                    ->where('registration_id', $registration->id)
                    ->first();

                if (!$existingEntry) {
                    LeaderboardEntry::create([
                        'competition_id' => $competition->id,
                        'registration_id' => $registration->id,
                        'team_name' => $registration->team_name ?? $registration->user->name,
                        'participant_name' => $registration->user->name,
                        'institution' => $registration->institution,
                        'score' => round($avgScore, 2),
                        'victory_points' => $victoryPoints,
                        'rank' => $rank,
                        'rank_type' => $rankType,
                        'is_active' => true,
                        'computed_at' => now(),
                    ]);
                    $totalLeaderboard++;
                }

                $rank++;
            }
        }

        // Update competitions to show leaderboard
        Competition::query()->update(['show_leaderboard' => true]);

        $this->command->info("   ✓ Created {$totalScores} scores from juries");
        $this->command->info("   ✓ Created {$totalLeaderboard} leaderboard entries");
        $this->command->info('✅ Leaderboard seeding completed!');
    }

    /**
     * Calculate victory points based on tournament-style ranking
     * Higher rank = more victory points
     */
    private function calculateVictoryPoints(int $rank, int $totalParticipants, float $avgScore): int
    {
        // Base victory points from placement
        $placementPoints = [
            1 => 100,  // Champion
            2 => 85,   // Runner-up
            3 => 70,   // 3rd place
            4 => 60,   // 4th place
            5 => 50,   // 5th place
        ];

        if ($rank <= 5) {
            $basePoints = $placementPoints[$rank];
        } else {
            // For lower ranks, decrease points gradually
            $basePoints = max(10, 45 - (($rank - 6) * 2));
        }

        // Add bonus points based on score performance (up to 20 extra points)
        $scoreBonus = (int) (($avgScore / 100) * 20);

        return $basePoints + $scoreBonus;
    }

    private function generateCriteriaScores(string $category): array
    {
        $criteriaTemplates = [
            'infographic' => [
                ['name' => 'Kreativitas & Originalitas', 'weight' => 25],
                ['name' => 'Kejelasan Informasi', 'weight' => 25],
                ['name' => 'Desain Visual', 'weight' => 20],
                ['name' => 'Relevansi Tema', 'weight' => 15],
                ['name' => 'Teknis & Layout', 'weight' => 15],
            ],
            'video' => [
                ['name' => 'Kreativitas & Konsep', 'weight' => 25],
                ['name' => 'Kualitas Produksi', 'weight' => 20],
                ['name' => 'Penyampaian Pesan', 'weight' => 20],
                ['name' => 'Editing & Efek Visual', 'weight' => 20],
                ['name' => 'Audio & Narasi', 'weight' => 15],
            ],
            'debate' => [
                ['name' => 'Argumentasi', 'weight' => 30],
                ['name' => 'Delivery & Presentasi', 'weight' => 20],
                ['name' => 'Rebuttal', 'weight' => 20],
                ['name' => 'Kerjasama Tim', 'weight' => 15],
                ['name' => 'Penguasaan Materi', 'weight' => 15],
            ],
            'scientific' => [
                ['name' => 'Orisinalitas Ide', 'weight' => 25],
                ['name' => 'Metodologi', 'weight' => 20],
                ['name' => 'Analisis Data', 'weight' => 20],
                ['name' => 'Penulisan Ilmiah', 'weight' => 20],
                ['name' => 'Kontribusi & Dampak', 'weight' => 15],
            ],
        ];

        // Determine which criteria to use based on category
        $categoryLower = strtolower($category ?? 'general');
        if (str_contains($categoryLower, 'infogra')) {
            $criteria = $criteriaTemplates['infographic'];
        } elseif (str_contains($categoryLower, 'video')) {
            $criteria = $criteriaTemplates['video'];
        } elseif (str_contains($categoryLower, 'debate') || str_contains($categoryLower, 'debat')) {
            $criteria = $criteriaTemplates['debate'];
        } elseif (str_contains($categoryLower, 'scientific') || str_contains($categoryLower, 'paper')) {
            $criteria = $criteriaTemplates['scientific'];
        } else {
            $criteria = $criteriaTemplates['infographic'];
        }

        // Generate scores for each criteria
        $result = [];
        foreach ($criteria as $c) {
            // Generate score between 60-100 for realism
            $maxScore = $c['weight'];
            $score = round(rand(60, 100) / 100 * $maxScore, 1);
            $result[] = [
                'name' => $c['name'],
                'weight' => $c['weight'],
                'score' => $score,
            ];
        }

        return $result;
    }

    private function generateJuryComment(float $score): string
    {
        if ($score >= 90) {
            $comments = [
                'Karya yang sangat luar biasa! Menunjukkan kreativitas dan kualitas tinggi.',
                'Excellent work! Sangat impressive dan memenuhi semua kriteria dengan baik.',
                'Outstanding! Salah satu karya terbaik yang saya review.',
                'Sangat memuaskan. Ide yang brilliant dengan eksekusi yang sempurna.',
            ];
        } elseif ($score >= 80) {
            $comments = [
                'Karya yang bagus dengan beberapa area yang bisa ditingkatkan.',
                'Good job! Secara keseluruhan sudah baik, perlu sedikit perbaikan minor.',
                'Solid work. Menunjukkan pemahaman yang baik terhadap tema.',
                'Bagus! Ada potensi besar untuk lebih berkembang.',
            ];
        } elseif ($score >= 70) {
            $comments = [
                'Cukup baik, namun masih ada beberapa aspek yang perlu diperbaiki.',
                'Average performance. Perlu lebih fokus pada detail.',
                'Decent effort. Bisa ditingkatkan dengan lebih banyak riset.',
                'Lumayan, tapi eksekusi bisa lebih baik lagi.',
            ];
        } else {
            $comments = [
                'Masih perlu banyak perbaikan. Perhatikan kriteria penilaian.',
                'Effort yang baik, tapi hasil belum optimal.',
                'Perlu lebih banyak latihan dan perbaikan.',
                'Ada potensi, tapi butuh pengembangan lebih lanjut.',
            ];
        }

        return $comments[array_rand($comments)];
    }

    private function generateFeedback(string $category): string
    {
        $feedbacks = [
            'Terus kembangkan kemampuan dan jangan berhenti berinovasi.',
            'Pertahankan kualitas dan terus tingkatkan kreativitas.',
            'Fokus pada detail dan pastikan setiap elemen mendukung pesan utama.',
            'Riset lebih mendalam akan sangat membantu meningkatkan kualitas karya.',
            'Kolaborasi tim yang baik akan menghasilkan karya yang lebih solid.',
            'Perhatikan aspek teknis tanpa mengorbankan kreativitas.',
            'Belajar dari karya-karya terbaik di bidang ini untuk inspirasi.',
            'Konsistensi adalah kunci untuk menghasilkan karya berkualitas.',
        ];

        return $feedbacks[array_rand($feedbacks)];
    }
}
