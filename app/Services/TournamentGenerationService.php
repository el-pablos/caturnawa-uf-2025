<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\DebateRound;
use App\Models\DebateMatch;
use App\Models\TeamStanding;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class TournamentGenerationService
{
    /**
     * Generate complete tournament structure for a debate competition
     */
    public function generateTournament(Competition $competition): array
    {
        return DB::transaction(function () use ($competition) {
            // Get all verified teams for this competition
            $teams = Registration::where('competition_id', $competition->id)
                ->where('status', 'VERIFIED')
                ->with('teamMembers')
                ->orderBy('created_at', 'asc')
                ->get();

            if ($teams->count() < 4) {
                throw new \Exception("Need at least 4 teams to generate tournament. Found {$teams->count()} teams.");
            }

            // Clear existing tournament structure
            $this->clearExistingTournament($competition);

            $createdRounds = [];
            $createdMatches = [];

            // 1. CREATE PRELIMINARY ROUNDS (4 rounds)
            for ($roundNum = 1; $roundNum <= 4; $roundNum++) {
                $round = DebateRound::create([
                    'competition_id' => $competition->id,
                    'stage' => 'PRELIMINARY',
                    'round_number' => $roundNum,
                    'session' => 1,
                    'round_name' => "Preliminary Round {$roundNum}",
                    'is_frozen' => false,
                ]);
                $createdRounds[] = $round;

                // Generate BP matches for this round
                $matches = $this->generateBPMatches($teams, $roundNum);
                
                foreach ($matches as $index => $matchTeams) {
                    $match = DebateMatch::create([
                        'round_id' => $round->id,
                        'match_number' => $index + 1,
                        'match_format' => 'BP',
                        'team1_id' => $matchTeams['og']->id,  // Opening Government
                        'team2_id' => $matchTeams['oo']->id,  // Opening Opposition
                        'team3_id' => $matchTeams['cg']->id,  // Closing Government
                        'team4_id' => $matchTeams['co']->id,  // Closing Opposition
                    ]);
                    $createdMatches[] = $match;
                }
            }

            // 2. CREATE SEMIFINAL ROUND (if enough teams)
            if ($teams->count() >= 8) {
                $semifinalRound = DebateRound::create([
                    'competition_id' => $competition->id,
                    'stage' => 'SEMIFINAL',
                    'round_number' => 1,
                    'session' => 1,
                    'round_name' => 'Semifinal',
                    'is_frozen' => false,
                ]);
                $createdRounds[] = $semifinalRound;

                // Create 2 semifinal matches (top 8 teams, 4 per match)
                $topTeams = $teams->take(8);
                
                for ($i = 0; $i < 2; $i++) {
                    $match = DebateMatch::create([
                        'round_id' => $semifinalRound->id,
                        'match_number' => $i + 1,
                        'match_format' => 'BP',
                        'team1_id' => $topTeams[$i * 4]->id,
                        'team2_id' => $topTeams[$i * 4 + 1]->id,
                        'team3_id' => $topTeams[$i * 4 + 2]->id,
                        'team4_id' => $topTeams[$i * 4 + 3]->id,
                    ]);
                    $createdMatches[] = $match;
                }
            }

            // 3. CREATE FINAL ROUND
            if ($teams->count() >= 4) {
                $finalRound = DebateRound::create([
                    'competition_id' => $competition->id,
                    'stage' => 'FINAL',
                    'round_number' => 1,
                    'session' => 1,
                    'round_name' => 'Grand Final',
                    'is_frozen' => false,
                ]);
                $createdRounds[] = $finalRound;

                // Create 1 final match (top 4 teams)
                $topTeams = $teams->take(4);
                
                $match = DebateMatch::create([
                    'round_id' => $finalRound->id,
                    'match_number' => 1,
                    'match_format' => 'BP',
                    'team1_id' => $topTeams[0]->id,
                    'team2_id' => $topTeams[1]->id,
                    'team3_id' => $topTeams[2]->id,
                    'team4_id' => $topTeams[3]->id,
                ]);
                $createdMatches[] = $match;
            }

            // Initialize team standings
            foreach ($teams as $team) {
                TeamStanding::create([
                    'registration_id' => $team->id,
                    'matches_played' => 0,
                    'team_points' => 0,
                    'speaker_points' => 0,
                    'average_speaker_points' => 0,
                    'avg_position' => 0,
                    'first_places' => 0,
                    'second_places' => 0,
                    'third_places' => 0,
                    'fourth_places' => 0,
                ]);
            }

            return [
                'rounds' => $createdRounds,
                'matches' => $createdMatches,
                'teams_count' => $teams->count(),
            ];
        });
    }

    /**
     * Clear existing tournament structure for a competition
     */
    protected function clearExistingTournament(Competition $competition): void
    {
        // Delete in correct order due to foreign key constraints
        DB::table('debate_scores')
            ->whereIn('match_id', function ($query) use ($competition) {
                $query->select('id')
                    ->from('debate_matches')
                    ->whereIn('round_id', function ($subQuery) use ($competition) {
                        $subQuery->select('id')
                            ->from('debate_rounds')
                            ->where('competition_id', $competition->id);
                    });
            })
            ->delete();

        DB::table('debate_matches')
            ->whereIn('round_id', function ($query) use ($competition) {
                $query->select('id')
                    ->from('debate_rounds')
                    ->where('competition_id', $competition->id);
            })
            ->delete();

        DebateRound::where('competition_id', $competition->id)->delete();

        TeamStanding::whereIn('registration_id', function ($query) use ($competition) {
            $query->select('id')
                ->from('registrations')
                ->where('competition_id', $competition->id);
        })->delete();
    }

    /**
     * Generate British Parliamentary matches (4 teams per match)
     */
    protected function generateBPMatches(Collection $teams, int $roundNumber): array
    {
        $matches = [];
        $shuffledTeams = $teams->values()->all();

        // For round 1, shuffle randomly
        if ($roundNumber === 1) {
            shuffle($shuffledTeams);
        }
        // For later rounds, teams would be sorted by standings
        // This will be implemented when we add power-pairing

        // Group teams into matches of 4 (BP format)
        for ($i = 0; $i < count($shuffledTeams); $i += 4) {
            if ($i + 3 < count($shuffledTeams)) {
                $matches[] = [
                    'og' => $shuffledTeams[$i],       // Opening Government
                    'oo' => $shuffledTeams[$i + 1],   // Opening Opposition
                    'cg' => $shuffledTeams[$i + 2],   // Closing Government
                    'co' => $shuffledTeams[$i + 3],   // Closing Opposition
                ];
            }
        }

        return $matches;
    }

    /**
     * Get tournament status for a competition
     */
    public function getTournamentStatus(Competition $competition): array
    {
        $rounds = DebateRound::where('competition_id', $competition->id)
            ->with(['matches.team1', 'matches.team2', 'matches.team3', 'matches.team4', 'matches.scores'])
            ->orderBy('stage')
            ->orderBy('round_number')
            ->get();

        $teams = Registration::where('competition_id', $competition->id)
            ->where('status', 'VERIFIED')
            ->count();

        $totalMatches = $rounds->sum(fn($round) => $round->matches->count());
        $completedMatches = $rounds->sum(fn($round) => 
            $round->matches->filter(fn($match) => $match->scores->count() > 0)->count()
        );

        $byStage = $rounds->groupBy('stage')->map(fn($stageRounds) => 
            $stageRounds->sum(fn($round) => $round->matches->count())
        );

        return [
            'total_teams' => $teams,
            'total_rounds' => $rounds->count(),
            'total_matches' => $totalMatches,
            'completed_matches' => $completedMatches,
            'by_stage' => $byStage,
            'rounds' => $rounds,
        ];
    }
}

