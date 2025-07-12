<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\User;
use App\Models\LeaderboardEntry;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaderboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing leaderboard entries
        LeaderboardEntry::truncate();

        // Get all competitions
        $competitions = Competition::all();

        if ($competitions->isEmpty()) {
            $this->command->error('No competitions found. Please run UnasFestCompetitionSeeder first.');
            return;
        }

        // Dummy teams data for each competition
        $competitionTeams = [
            'dcc' => [
                ['team_name' => 'Data Scientists United', 'participant_name' => 'Ahmad Rifki', 'institution' => 'Universitas Indonesia', 'score' => 94.8, 'status' => 'Final'],
                ['team_name' => 'Analytics Pro', 'participant_name' => 'Sari Dewi', 'institution' => 'Institut Teknologi Bandung', 'score' => 91.5, 'status' => 'Final'],
                ['team_name' => 'Big Data Heroes', 'participant_name' => 'Budi Santoso', 'institution' => 'Universitas Gadjah Mada', 'score' => 89.2, 'status' => 'Final'],
                ['team_name' => 'Data Mining Squad', 'participant_name' => 'Rina Pratiwi', 'institution' => 'Universitas Brawijaya', 'score' => 87.1, 'status' => 'Penyisihan'],
                ['team_name' => 'Machine Learning Team', 'participant_name' => 'Joko Widodo', 'institution' => 'Institut Teknologi Sepuluh Nopember', 'score' => 85.9, 'status' => 'Penyisihan'],
                ['team_name' => 'AI Specialists', 'participant_name' => 'Maya Sari', 'institution' => 'Universitas Diponegoro', 'score' => 84.7, 'status' => 'Penyisihan'],
                ['team_name' => 'Neural Network Pro', 'participant_name' => 'Andi Setiawan', 'institution' => 'Universitas Padjadjaran', 'score' => 83.3, 'status' => 'Penyisihan'],
                ['team_name' => 'Deep Learning Squad', 'participant_name' => 'Lisa Handayani', 'institution' => 'Universitas Sebelas Maret', 'score' => 82.1, 'status' => 'Penyisihan'],
            ],
            'spc' => [
                ['team_name' => 'Research Innovators', 'participant_name' => 'Dr. Indra Mahasiswa', 'institution' => 'Universitas Airlangga', 'score' => 92.3, 'status' => 'Final'],
                ['team_name' => 'Academic Excellence', 'participant_name' => 'Prof. Sinta Dewi', 'institution' => 'Universitas Padjadjaran', 'score' => 90.1, 'status' => 'Final'],
                ['team_name' => 'Scientific Writers', 'participant_name' => 'Dr. Bambang Susilo', 'institution' => 'Universitas Diponegoro', 'score' => 88.7, 'status' => 'Final'],
                ['team_name' => 'Knowledge Seekers', 'participant_name' => 'Mega Fitriani', 'institution' => 'Universitas Hasanuddin', 'score' => 86.4, 'status' => 'Semifinal'],
                ['team_name' => 'Research Masters', 'participant_name' => 'Hadi Santoso', 'institution' => 'Universitas Sebelas Maret', 'score' => 84.2, 'status' => 'Semifinal'],
                ['team_name' => 'Academic Scholars', 'participant_name' => 'Fitri Rahayu', 'institution' => 'Universitas Lampung', 'score' => 83.8, 'status' => 'Semifinal'],
                ['team_name' => 'Paper Champions', 'participant_name' => 'Rio Pramono', 'institution' => 'Universitas Jember', 'score' => 82.5, 'status' => 'Semifinal'],
                ['team_name' => 'Science Explorer', 'participant_name' => 'Nina Kartika', 'institution' => 'Universitas Riau', 'score' => 81.3, 'status' => 'Semifinal'],
            ],
            'english-debate' => [
                ['team_name' => 'Oxford Speakers', 'participant_name' => 'William Anderson', 'institution' => 'Institut Pertanian Bogor', 'score' => 93.6, 'status' => 'Final'],
                ['team_name' => 'Debate Champions', 'participant_name' => 'Sarah Mitchell', 'institution' => 'Universitas Gadjah Mada', 'score' => 91.8, 'status' => 'Final'],
                ['team_name' => 'Eloquent Voices', 'participant_name' => 'Michael Johnson', 'institution' => 'Universitas Indonesia', 'score' => 89.4, 'status' => 'Final'],
                ['team_name' => 'Parliamentary Pros', 'participant_name' => 'Emma Thompson', 'institution' => 'Universitas Brawijaya', 'score' => 87.7, 'status' => 'Semifinal'],
                ['team_name' => 'Rhetoric Masters', 'participant_name' => 'David Wilson', 'institution' => 'Universitas Andalas', 'score' => 85.3, 'status' => 'Semifinal'],
                ['team_name' => 'Speaking Stars', 'participant_name' => 'Jessica Brown', 'institution' => 'Institut Teknologi Sepuluh Nopember', 'score' => 84.9, 'status' => 'Semifinal'],
                ['team_name' => 'Debate Warriors', 'participant_name' => 'James Davis', 'institution' => 'Universitas Riau', 'score' => 83.2, 'status' => 'Semifinal'],
                ['team_name' => 'Eloquent Squad', 'participant_name' => 'Sophie Taylor', 'institution' => 'Universitas Sriwijaya', 'score' => 82.6, 'status' => 'Semifinal'],
            ],
            'kdbi' => [
                ['team_name' => 'Penceramah Ulung', 'participant_name' => 'Achmad Prasetyo', 'institution' => 'Universitas Indonesia', 'score' => 94.2, 'status' => 'Final'],
                ['team_name' => 'Debater Nusantara', 'participant_name' => 'Siti Nurhaliza', 'institution' => 'Institut Teknologi Bandung', 'score' => 92.1, 'status' => 'Final'],
                ['team_name' => 'Orator Muda', 'participant_name' => 'Bambang Hermanto', 'institution' => 'Universitas Gadjah Mada', 'score' => 89.8, 'status' => 'Final'],
                ['team_name' => 'Pembicara Handal', 'participant_name' => 'Dewi Lestari', 'institution' => 'Universitas Airlangga', 'score' => 87.5, 'status' => 'Semifinal'],
                ['team_name' => 'Retorika Indonesia', 'participant_name' => 'Agus Susanto', 'institution' => 'Universitas Padjadjaran', 'score' => 85.7, 'status' => 'Semifinal'],
                ['team_name' => 'Pidato Cemerlang', 'participant_name' => 'Rika Safitri', 'institution' => 'Universitas Diponegoro', 'score' => 84.3, 'status' => 'Semifinal'],
                ['team_name' => 'Bahasa Kita', 'participant_name' => 'Firman Hidayat', 'institution' => 'Universitas Sebelas Maret', 'score' => 83.1, 'status' => 'Semifinal'],
                ['team_name' => 'Kata Bijak', 'participant_name' => 'Indah Permata', 'institution' => 'Universitas Brawijaya', 'score' => 81.9, 'status' => 'Semifinal'],
            ],
            'short-movie' => [
                ['team_name' => 'Cinematic Vision', 'participant_name' => 'Ario Bayu', 'institution' => 'Institut Seni Budaya Indonesia', 'score' => 95.1, 'status' => 'Final'],
                ['team_name' => 'Movie Makers', 'participant_name' => 'Tara Basro', 'institution' => 'Universitas Multimedia', 'score' => 92.7, 'status' => 'Final'],
                ['team_name' => 'Film Creators', 'participant_name' => 'Reza Rahadian', 'institution' => 'Institut Teknologi Bandung', 'score' => 90.3, 'status' => 'Final'],
                ['team_name' => 'Story Tellers', 'participant_name' => 'Dian Sastro', 'institution' => 'Universitas Indonesia', 'score' => 88.9, 'status' => 'Semifinal'],
                ['team_name' => 'Visual Artists', 'participant_name' => 'Nicholas Saputra', 'institution' => 'Universitas Gadjah Mada', 'score' => 86.4, 'status' => 'Semifinal'],
                ['team_name' => 'Creative Cinema', 'participant_name' => 'Raisa Andriana', 'institution' => 'Institut Seni Budaya Indonesia', 'score' => 85.2, 'status' => 'Semifinal'],
                ['team_name' => 'Film Academy', 'participant_name' => 'Chicco Jerikho', 'institution' => 'Universitas Trisakti', 'score' => 83.7, 'status' => 'Semifinal'],
                ['team_name' => 'Motion Picture', 'participant_name' => 'Prisia Nasution', 'institution' => 'Universitas Pelita Harapan', 'score' => 82.4, 'status' => 'Semifinal'],
            ],
            'photography' => [
                ['team_name' => 'Lens Masters', 'participant_name' => 'Rio Motret', 'institution' => 'Institut Seni Budaya Indonesia', 'score' => 96.3, 'status' => 'Final'],
                ['team_name' => 'Photo Experts', 'participant_name' => 'Sari Lensa', 'institution' => 'Universitas Trisakti', 'score' => 93.8, 'status' => 'Final'],
                ['team_name' => 'Visual Storytellers', 'participant_name' => 'Andi Shutter', 'institution' => 'Universitas Pelita Harapan', 'score' => 91.2, 'status' => 'Final'],
                ['team_name' => 'Shutter Speed', 'participant_name' => 'Maya Camera', 'institution' => 'Universitas Bina Nusantara', 'score' => 89.5, 'status' => 'Semifinal'],
                ['team_name' => 'Creative Shots', 'participant_name' => 'Budi Foto', 'institution' => 'Universitas Indonesia', 'score' => 87.1, 'status' => 'Semifinal'],
                ['team_name' => 'Picture Perfect', 'participant_name' => 'Lisa Focus', 'institution' => 'Institut Teknologi Bandung', 'score' => 85.8, 'status' => 'Semifinal'],
                ['team_name' => 'Frame Artists', 'participant_name' => 'Joko Exposure', 'institution' => 'Universitas Gadjah Mada', 'score' => 84.6, 'status' => 'Semifinal'],
                ['team_name' => 'Light Catchers', 'participant_name' => 'Nina Aperture', 'institution' => 'Universitas Diponegoro', 'score' => 83.3, 'status' => 'Semifinal'],
            ],
        ];

        $this->command->info('Creating dummy leaderboard entries...');

        foreach ($competitions as $competition) {
            $slug = $competition->slug;
            
            if (!isset($competitionTeams[$slug])) {
                $this->command->warn("No dummy data found for competition: {$competition->name}");
                continue;
            }

            $teams = $competitionTeams[$slug];
            
            $this->command->info("Creating entries for: {$competition->name}");

            foreach ($teams as $index => $teamData) {
                // Create dummy user if not exists
                $user = User::firstOrCreate(
                    ['email' => strtolower(str_replace(' ', '.', $teamData['participant_name'])) . '@student.example.com'],
                    [
                        'name' => $teamData['participant_name'],
                        'phone' => '08' . rand(1000000000, 9999999999),
                        'email_verified_at' => now(),
                        'password' => bcrypt('password'),
                        'participant_status' => 'Mahasiswa Eksternal',
                    ]
                );

                // Create dummy registration if not exists
                $registration = Registration::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'competition_id' => $competition->id,
                    ],
                    [
                        'team_name' => $teamData['team_name'],
                        'institution' => $teamData['institution'],
                        'status' => 'approved',
                        'payment_status' => 'paid',
                        'is_paid' => true,
                        'registration_number' => 'REG' . $competition->id . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(1, 5)),
                    ]
                );

                // Create leaderboard entry
                LeaderboardEntry::create([
                    'competition_id' => $competition->id,
                    'registration_id' => $registration->id,
                    'team_name' => $teamData['team_name'],
                    'participant_name' => $teamData['participant_name'],
                    'institution' => $teamData['institution'],
                    'score' => $teamData['score'],
                    'victory_points' => $this->calculateVictoryPoints($teamData['score']),
                    'rank' => $index + 1,
                    'rank_type' => 'position',
                    'is_active' => true,
                    'computed_at' => now()->subHours(rand(1, 24)),
                    'created_at' => now()->subDays(rand(1, 7)),
                    'updated_at' => now()->subHours(rand(1, 12)),
                ]);

                $this->command->line("  ✓ Created entry for: {$teamData['team_name']} (Rank: " . ($index + 1) . ", Score: {$teamData['score']})");
            }
        }

        $this->command->info('');
        $this->command->info('🏆 Leaderboard seeding completed successfully!');
        $this->command->info('Total entries created: ' . LeaderboardEntry::count());
        $this->command->info('');
        $this->command->info('📊 Competition Summary:');
        foreach ($competitions as $competition) {
            $entryCount = LeaderboardEntry::where('competition_id', $competition->id)->count();
            $this->command->line("  • {$competition->name}: {$entryCount} entries");
        }
    }

    /**
     * Calculate victory points based on score
     */
    private function calculateVictoryPoints($score)
    {
        if ($score >= 95) return 100;
        if ($score >= 90) return 80;
        if ($score >= 85) return 60;
        if ($score >= 80) return 40;
        if ($score >= 75) return 20;
        return 10;
    }
}
