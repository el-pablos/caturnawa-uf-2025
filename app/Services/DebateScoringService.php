<?php

namespace App\Services;

use App\Models\DebateMatch;
use App\Models\DebateScore;
use App\Models\TeamMember;
use Illuminate\Support\Facades\DB;

class DebateScoringService
{
    /**
     * Submit judge scores for a BP debate match
     * 
     * @param string $matchId
     * @param string $judgeId
     * @param array $scores Format: [
     *   'team1' => ['speaker1' => 75.5, 'speaker2' => 76.0, 'teamScore' => 151.5],
     *   'team2' => ['speaker1' => 74.0, 'speaker2' => 75.0, 'teamScore' => 149.0],
     *   'team3' => ['speaker1' => 73.5, 'speaker2' => 74.5, 'teamScore' => 148.0],
     *   'team4' => ['speaker1' => 72.0, 'speaker2' => 73.0, 'teamScore' => 145.0],
     *   'ranking' => [0, 1, 2, 3] // Team indices in order of placement (1st, 2nd, 3rd, 4th)
     * ]
     * @return array
     */
    public function submitJudgeScores(string $matchId, string $judgeId, array $scores): array
    {
        return DB::transaction(function () use ($matchId, $judgeId, $scores) {
            // Get match with all teams and their members
            $match = DebateMatch::with([
                'team1.teamMembers.participant',
                'team2.teamMembers.participant',
                'team3.teamMembers.participant',
                'team4.teamMembers.participant',
                'round'
            ])->findOrFail($matchId);

            // Delete existing scores for this match
            DebateScore::where('match_id', $matchId)->delete();

            // BP positions mapping
            $positions = ['OG', 'OO', 'CG', 'CO'];
            $bpPositions = [
                ['PM', 'DPM'],    // Opening Government
                ['LO', 'DLO'],    // Opening Opposition
                ['MG', 'GW'],     // Closing Government
                ['MO', 'OW']      // Closing Opposition
            ];

            $teams = [
                $match->team1,
                $match->team2,
                $match->team3,
                $match->team4
            ];

            $scoresToCreate = [];

            // Create scores for each speaker
            foreach ($teams as $teamIndex => $team) {
                if (!$team) continue;

                $teamScoreData = $scores["team" . ($teamIndex + 1)] ?? null;
                if (!$teamScoreData) continue;

                $teamMembers = $team->teamMembers;

                for ($speakerIndex = 0; $speakerIndex < min(2, $teamMembers->count()); $speakerIndex++) {
                    $member = $teamMembers[$speakerIndex];
                    $speakerScore = $teamScoreData["speaker" . ($speakerIndex + 1)] ?? 0;

                    $scoresToCreate[] = [
                        'match_id' => $matchId,
                        'team_member_id' => $member->id,
                        'judge_id' => $judgeId,
                        'score' => $speakerScore,
                        'bp_position' => $bpPositions[$teamIndex][$speakerIndex],
                        'team_position' => $positions[$teamIndex],
                        'speaker_rank' => array_search($teamIndex, $scores['ranking']) + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Insert all scores
            if (!empty($scoresToCreate)) {
                DebateScore::insert($scoresToCreate);
            }

            // Update match with rankings and completion
            $ranking = $scores['ranking'];
            $match->update([
                'first_place_team_id' => $teams[$ranking[0]]->id ?? null,
                'second_place_team_id' => $teams[$ranking[1]]->id ?? null,
                'third_place_team_id' => $teams[$ranking[2]]->id ?? null,
                'fourth_place_team_id' => $teams[$ranking[3]]->id ?? null,
                'completed_at' => now(),
            ]);

            // Update team standings
            $this->updateTeamStandings($match, $scores);

            return [
                'success' => true,
                'message' => 'Scores submitted successfully',
                'match_id' => $matchId,
                'scores_created' => count($scoresToCreate),
            ];
        });
    }

    /**
     * Update team standings after scoring
     */
    protected function updateTeamStandings(DebateMatch $match, array $scores): void
    {
        $teams = [
            $match->team1,
            $match->team2,
            $match->team3,
            $match->team4
        ];

        $ranking = $scores['ranking'];

        // BP team points: 1st = 3pts, 2nd = 2pts, 3rd = 1pt, 4th = 0pts
        $teamPointsMap = [3, 2, 1, 0];

        foreach ($ranking as $position => $teamIndex) {
            $team = $teams[$teamIndex];
            if (!$team) continue;

            $teamPoints = $teamPointsMap[$position];
            $teamScoreData = $scores["team" . ($teamIndex + 1)];
            $speakerPoints = $teamScoreData['teamScore'] ?? 0;

            // Get or create team standing
            $standing = $team->standing ?? \App\Models\TeamStanding::create([
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

            // Update standings
            $standing->updateStandings(
                $teamPoints,
                $speakerPoints,
                $position + 1,
                $match->round->stage
            );
        }
    }

    /**
     * Get judge's assigned matches
     */
    public function getJudgeMatches(string $judgeId, ?string $stage = null, ?int $roundNumber = null): array
    {
        $query = DebateMatch::with([
            'round.competition',
            'team1.participant',
            'team1.teamMembers.participant',
            'team2.participant',
            'team2.teamMembers.participant',
            'team3.participant',
            'team3.teamMembers.participant',
            'team4.participant',
            'team4.teamMembers.participant',
            'scores'
        ])->where('judge_id', $judgeId);

        if ($stage) {
            $query->whereHas('round', function ($q) use ($stage) {
                $q->where('stage', $stage);
            });
        }

        if ($roundNumber) {
            $query->whereHas('round', function ($q) use ($roundNumber) {
                $q->where('round_number', $roundNumber);
            });
        }

        return $query->orderBy('scheduled_at')->get()->toArray();
    }

    /**
     * Get match scores
     */
    public function getMatchScores(string $matchId): array
    {
        $match = DebateMatch::with([
            'scores.teamMember.participant',
            'team1',
            'team2',
            'team3',
            'team4',
            'round'
        ])->findOrFail($matchId);

        $scores = $match->scores->groupBy('team_position');

        return [
            'match' => $match,
            'scores' => $scores,
            'is_completed' => $match->isCompleted(),
        ];
    }

    /**
     * Validate BP scores
     */
    public function validateBPScores(array $scores): array
    {
        $errors = [];

        // Check required fields
        if (!isset($scores['ranking']) || count($scores['ranking']) !== 4) {
            $errors[] = 'Ranking must contain exactly 4 team positions';
        }

        // Validate each team's scores
        for ($i = 1; $i <= 4; $i++) {
            $teamKey = "team{$i}";
            if (!isset($scores[$teamKey])) {
                $errors[] = "Missing scores for {$teamKey}";
                continue;
            }

            $teamScores = $scores[$teamKey];

            // Validate speaker scores (70-80 range for BP)
            for ($j = 1; $j <= 2; $j++) {
                $speakerKey = "speaker{$j}";
                if (!isset($teamScores[$speakerKey])) {
                    $errors[] = "Missing {$speakerKey} score for {$teamKey}";
                    continue;
                }

                $score = $teamScores[$speakerKey];
                if ($score < 70 || $score > 80) {
                    $errors[] = "Speaker score must be between 70-80 (got {$score} for {$teamKey} {$speakerKey})";
                }
            }

            // Validate team score
            if (!isset($teamScores['teamScore'])) {
                $errors[] = "Missing teamScore for {$teamKey}";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }
}

