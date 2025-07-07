<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\CompetitionRound;
use App\Models\RoundMatch;
use App\Models\TeamMatchup;
use App\Models\User;

class MatchupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get KDBI competition
        $kdbi = Competition::where('slug', 'kdbi')->first();
        if (!$kdbi) {
            $this->command->error('KDBI competition not found. Please run UnasFestCompetitionSeeder first.');
            return;
        }

        // Create sample registrations for KDBI
        $this->createSampleRegistrations($kdbi);

        // Create matches and matchups for semifinal round
        $this->createSemifinalMatches($kdbi);

        $this->command->info('Sample matchups created successfully!');
    }

    private function createSampleRegistrations($competition)
    {
        // Get peserta users
        $pesertaUsers = User::role('peserta')->take(12)->get();

        $teams = [
            ['name' => 'AsaH', 'institution' => 'Universitas Gunadarma'],
            ['name' => 'Dazzling Diva', 'institution' => 'Universitas Pembangunan Jaya'],
            ['name' => 'SparkGreen', 'institution' => 'Universitas Esa Unggul'],
            ['name' => 'EcoEnergy Champions', 'institution' => 'Universitas Brawijaya'],
            ['name' => 'ZEBRAW', 'institution' => 'Universitas Ciputra'],
            ['name' => 'Agrispeech', 'institution' => 'IPB University'],
            ['name' => 'Trisakti Reformasi Berprestasi', 'institution' => 'Universitas Trisakti'],
            ['name' => 'Econova', 'institution' => 'Universitas Bunda Mulia'],
            ['name' => 'Deco UAD', 'institution' => 'Universitas Ahmad Dahlan'],
            ['name' => 'Eco Femmes', 'institution' => 'Universitas Sahid'],
            ['name' => 'Wira Ekologi', 'institution' => 'Politeknik Negeri Batam'],
            ['name' => 'Valkyrie', 'institution' => 'Universitas Singaperbangsa Karawang'],
        ];

        foreach ($teams as $index => $teamData) {
            if (isset($pesertaUsers[$index])) {
                $user = $pesertaUsers[$index];

                Registration::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'competition_id' => $competition->id,
                    ],
                    [
                        'registration_number' => 'KDBI-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                        'team_name' => $teamData['name'],
                        'team_members' => [
                            [
                                'name' => $user->name,
                                'email' => $user->email,
                                'phone' => $user->phone,
                                'foto' => null,
                            ],
                            [
                                'name' => 'Partner ' . ($index + 1),
                                'email' => 'partner' . ($index + 1) . '@example.com',
                                'phone' => '0812345678' . ($index + 1),
                                'foto' => null,
                            ]
                        ],
                        'institution' => $teamData['institution'],
                        'phone' => $user->phone,
                        'gender' => $user->gender ?? 'male',
                        'education_level' => 'S1',
                        'amount' => $competition->getCurrentPriceAttribute(),
                        'status' => 'confirmed',
                        'registered_at' => now()->subDays(rand(1, 30)),
                        'confirmed_at' => now()->subDays(rand(1, 15)),
                    ]
                );
            }
        }
    }

    private function createSemifinalMatches($competition)
    {
        // Get semifinal round
        $semifinalRound = $competition->rounds()->where('round_type', 'semifinal')->first();
        if (!$semifinalRound) {
            $this->command->error('Semifinal round not found for KDBI.');
            return;
        }

        // Get confirmed registrations
        $registrations = $competition->registrations()->where('status', 'confirmed')->get();
        if ($registrations->count() < 4) {
            $this->command->error('Not enough confirmed registrations for matches.');
            return;
        }

        // Get juri users
        $juriUsers = User::role('juri')->take(3)->get();

        // Create Round 1
        $round1 = RoundMatch::updateOrCreate(
            [
                'competition_round_id' => $semifinalRound->id,
                'match_name' => 'Round 1',
            ],
            [
                'room_name' => 'Breakout Room 1',
                'motion' => 'Dewan ini percaya bahwa gerakan lingkungan harus secara signifikan memprioritaskan upaya kampanye mereka pada perubahan perilaku individu dibandingkan dengan mengubah perilaku perusahaan',
                'scheduled_at' => now()->addDays(5)->setTime(9, 0),
                'status' => 'completed',
            ]
        );

        // Create matchups for Round 1
        $positions = ['OG', 'OO', 'CG', 'CO'];
        $victoryPoints = [2, 0, 3, 1]; // Sample victory points
        $teamScores = [76.5, 75, 78, 74]; // Sample team scores

        for ($i = 0; $i < 4 && $i < $registrations->count(); $i++) {
            TeamMatchup::updateOrCreate(
                [
                    'round_match_id' => $round1->id,
                    'registration_id' => $registrations[$i]->id,
                ],
                [
                    'position' => $positions[$i],
                    'jury_id' => $juriUsers->first()?->id,
                    'team_score' => $teamScores[$i],
                    'victory_points' => $victoryPoints[$i],
                    'ranking' => array_search($victoryPoints[$i], array_reverse(array_unique($victoryPoints), true)) + 1,
                    'individual_scores' => [
                        $teamScores[$i] - 1,
                        $teamScores[$i] + 1,
                    ],
                ]
            );
        }

        // Create Round 2 if we have more teams
        if ($registrations->count() >= 8) {
            $round2 = RoundMatch::updateOrCreate(
                [
                    'competition_round_id' => $semifinalRound->id,
                    'match_name' => 'Round 2',
                ],
                [
                    'room_name' => 'Breakout Room 2',
                    'motion' => 'Dewan ini percaya bahwa gerakan lingkungan harus secara signifikan memprioritaskan upaya kampanye mereka pada perubahan perilaku individu dibandingkan dengan mengubah perilaku perusahaan',
                    'scheduled_at' => now()->addDays(5)->setTime(11, 0),
                    'status' => 'completed',
                ]
            );

            // Create matchups for Round 2
            $victoryPoints2 = [3, 2, 1, 0];
            $teamScores2 = [79, 77.5, 77, 75];

            for ($i = 4; $i < 8 && $i < $registrations->count(); $i++) {
                TeamMatchup::updateOrCreate(
                    [
                        'round_match_id' => $round2->id,
                        'registration_id' => $registrations[$i]->id,
                    ],
                    [
                        'position' => $positions[$i - 4],
                        'jury_id' => $juriUsers->skip(1)->first()?->id,
                        'team_score' => $teamScores2[$i - 4],
                        'victory_points' => $victoryPoints2[$i - 4],
                        'ranking' => array_search($victoryPoints2[$i - 4], array_reverse(array_unique($victoryPoints2), true)) + 1,
                        'individual_scores' => [
                            $teamScores2[$i - 4] - 1,
                            $teamScores2[$i - 4] + 1,
                        ],
                    ]
                );
            }
        }

        // Update round status
        $semifinalRound->update(['status' => 'completed']);
    }
}
