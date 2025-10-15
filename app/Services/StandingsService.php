<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\TeamStanding;
use App\Models\Registration;
use App\Models\DebateRound;
use Illuminate\Support\Collection;

class StandingsService
{
    /**
     * Get current standings for a competition
     */
    public function getStandings(Competition $competition, ?string $stage = null): Collection
    {
        $query = TeamStanding::with([
            'registration.participant',
            'registration.teamMembers.participant'
        ])
        ->whereHas('registration', function ($q) use ($competition) {
            $q->where('competition_id', $competition->id);
        });

        // Order by team points, then speaker points, then avg position
        $standings = $query->orderByTeamPoints()->get();

        // Add rank to each standing
        $standings->each(function ($standing, $index) {
            $standing->rank = $index + 1;
        });

        return $standings;
    }

    /**
     * Get standings by stage (PRELIMINARY, SEMIFINAL, FINAL)
     */
    public function getStandingsByStage(Competition $competition, string $stage): Collection
    {
        $standings = $this->getStandings($competition);

        // Filter based on stage-specific metrics
        return $standings->map(function ($standing) use ($stage) {
            $stagePrefix = strtolower($stage);
            
            if (in_array($stage, ['PRELIMINARY', 'SEMIFINAL', 'FINAL'])) {
                $standing->stage_team_points = $standing->{"{$stagePrefix}_team_points"} ?? 0;
                $standing->stage_speaker_points = $standing->{"{$stagePrefix}_speaker_points"} ?? 0;
                $standing->stage_avg_position = $standing->{"{$stagePrefix}_avg_position"} ?? 0;
            }
            
            return $standing;
        });
    }

    /**
     * Recalculate all standings for a competition
     */
    public function recalculateStandings(Competition $competition): array
    {
        $teams = Registration::where('competition_id', $competition->id)
            ->where('status', 'VERIFIED')
            ->get();

        $recalculated = 0;

        foreach ($teams as $team) {
            // Get or create standing
            $standing = TeamStanding::firstOrCreate(
                ['registration_id' => $team->id],
                [
                    'matches_played' => 0,
                    'team_points' => 0,
                    'speaker_points' => 0,
                    'average_speaker_points' => 0,
                    'avg_position' => 0,
                    'first_places' => 0,
                    'second_places' => 0,
                    'third_places' => 0,
                    'fourth_places' => 0,
                ]
            );

            // Reset standings
            $standing->update([
                'matches_played' => 0,
                'team_points' => 0,
                'speaker_points' => 0,
                'average_speaker_points' => 0,
                'avg_position' => 0,
                'first_places' => 0,
                'second_places' => 0,
                'third_places' => 0,
                'fourth_places' => 0,
                'prelim_team_points' => 0,
                'prelim_speaker_points' => 0,
                'prelim_avg_position' => 0,
                'semifinal_team_points' => 0,
                'semifinal_speaker_points' => 0,
                'semifinal_avg_position' => 0,
                'final_team_points' => 0,
                'final_speaker_points' => 0,
                'final_avg_position' => 0,
            ]);

            // Recalculate from all completed matches
            $this->recalculateTeamStanding($team);
            $recalculated++;
        }

        return [
            'success' => true,
            'teams_recalculated' => $recalculated,
        ];
    }

    /**
     * Recalculate standings for a specific team
     */
    protected function recalculateTeamStanding(Registration $team): void
    {
        $standing = $team->standing;
        if (!$standing) return;

        // Get all completed matches for this team
        $matches = \App\Models\DebateMatch::with(['round', 'scores'])
            ->where(function ($query) use ($team) {
                $query->where('team1_id', $team->id)
                    ->orWhere('team2_id', $team->id)
                    ->orWhere('team3_id', $team->id)
                    ->orWhere('team4_id', $team->id);
            })
            ->whereNotNull('completed_at')
            ->get();

        foreach ($matches as $match) {
            // Determine team position in this match
            $position = $this->getTeamPosition($match, $team->id);
            if (!$position) continue;

            // Calculate team points (3, 2, 1, 0 for 1st, 2nd, 3rd, 4th)
            $teamPoints = [1 => 3, 2 => 2, 3 => 1, 4 => 0][$position] ?? 0;

            // Calculate speaker points for this team
            $speakerPoints = $match->scores()
                ->whereHas('teamMember', function ($q) use ($team) {
                    $q->where('registration_id', $team->id);
                })
                ->sum('score');

            // Update standings
            $standing->updateStandings(
                $teamPoints,
                $speakerPoints,
                $position,
                $match->round->stage
            );
        }
    }

    /**
     * Get team's position in a match (1-4)
     */
    protected function getTeamPosition(\App\Models\DebateMatch $match, int $teamId): ?int
    {
        if ($match->first_place_team_id === $teamId) return 1;
        if ($match->second_place_team_id === $teamId) return 2;
        if ($match->third_place_team_id === $teamId) return 3;
        if ($match->fourth_place_team_id === $teamId) return 4;
        
        return null;
    }

    /**
     * Get top teams for break rounds
     */
    public function getTopTeams(Competition $competition, int $count = 8): Collection
    {
        return TeamStanding::with([
            'registration.participant',
            'registration.teamMembers.participant'
        ])
        ->whereHas('registration', function ($q) use ($competition) {
            $q->where('competition_id', $competition->id);
        })
        ->orderByTeamPoints()
        ->limit($count)
        ->get();
    }

    /**
     * Export standings to array format
     */
    public function exportStandings(Competition $competition): array
    {
        $standings = $this->getStandings($competition);

        return $standings->map(function ($standing, $index) {
            return [
                'rank' => $index + 1,
                'team_name' => $standing->registration->team_name ?? 'Unknown',
                'institution' => $standing->registration->participant->institution ?? 'Unknown',
                'matches_played' => $standing->matches_played,
                'team_points' => $standing->team_points,
                'speaker_points' => number_format($standing->speaker_points, 2),
                'average_speaker_points' => number_format($standing->average_speaker_points, 2),
                'avg_position' => number_format($standing->avg_position, 2),
                'first_places' => $standing->first_places,
                'second_places' => $standing->second_places,
                'third_places' => $standing->third_places,
                'fourth_places' => $standing->fourth_places,
            ];
        })->toArray();
    }

    /**
     * Get speaker standings (individual speaker scores)
     */
    public function getSpeakerStandings(Competition $competition): Collection
    {
        $rounds = DebateRound::where('competition_id', $competition->id)->pluck('id');

        $speakerScores = \App\Models\DebateScore::with([
            'teamMember.participant',
            'teamMember.registration'
        ])
        ->whereHas('match', function ($q) use ($rounds) {
            $q->whereIn('round_id', $rounds);
        })
        ->get()
        ->groupBy('team_member_id')
        ->map(function ($scores, $teamMemberId) {
            $teamMember = $scores->first()->teamMember;
            $totalScore = $scores->sum('score');
            $avgScore = $scores->avg('score');
            $speechesCount = $scores->count();

            return [
                'team_member_id' => $teamMemberId,
                'speaker_name' => $teamMember->participant->full_name ?? 'Unknown',
                'team_name' => $teamMember->registration->team_name ?? 'Unknown',
                'total_score' => number_format($totalScore, 2),
                'average_score' => number_format($avgScore, 2),
                'speeches_count' => $speechesCount,
            ];
        })
        ->sortByDesc('total_score')
        ->values();

        return $speakerScores;
    }

    /**
     * Get comprehensive results for a competition
     */
    public function getComprehensiveResults(Competition $competition): array
    {
        return [
            'team_standings' => $this->exportStandings($competition),
            'speaker_standings' => $this->getSpeakerStandings($competition),
            'preliminary_standings' => $this->getStandingsByStage($competition, 'PRELIMINARY'),
            'semifinal_standings' => $this->getStandingsByStage($competition, 'SEMIFINAL'),
            'final_standings' => $this->getStandingsByStage($competition, 'FINAL'),
        ];
    }
}

