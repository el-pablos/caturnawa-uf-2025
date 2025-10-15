<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\TeamMatchup;
use App\Models\Registration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Service untuk menghitung leaderboard dan victory points
 * 
 * Menangani perhitungan ranking tim berdasarkan victory points
 * dan skor rata-rata untuk setiap babak kompetisi
 */
class LeaderboardService
{
    /**
     * Calculate leaderboard for a specific competition
     *
     * @param Competition $competition
     * @return Collection
     */
    public function calculateCompetitionLeaderboard(Competition $competition): Collection
    {
        // Get all confirmed registrations for this competition
        $registrations = $competition->registrations()
            ->where('status', 'confirmed')
            ->with(['user', 'teamMatchups.roundMatch.competitionRound'])
            ->get();

        $leaderboard = collect();

        foreach ($registrations as $registration) {
            $teamData = $this->calculateTeamStats($registration);
            $leaderboard->push($teamData);
        }

        // Sort by total victory points (desc), then by average score (desc)
        return $leaderboard->sortByDesc(function ($team) {
            return [$team['total_victory_points'], $team['average_score']];
        })->values();
    }

    /**
     * Calculate leaderboard for a specific round
     *
     * @param CompetitionRound $round
     * @return Collection
     */
    public function calculateRoundLeaderboard(CompetitionRound $round): Collection
    {
        // Get all team matchups for this round
        $teamMatchups = TeamMatchup::whereHas('roundMatch', function($query) use ($round) {
            $query->where('competition_round_id', $round->id);
        })
        ->with(['registration.user'])
        ->get();

        // Group by team and calculate stats
        $leaderboard = $teamMatchups->groupBy('registration_id')
            ->map(function($matchups) {
                $registration = $matchups->first()->registration;
                $totalVictoryPoints = $matchups->sum('victory_points');
                $averageScore = $matchups->whereNotNull('team_score')->avg('team_score');
                $matchesPlayed = $matchups->count();
                
                return [
                    'registration' => $registration,
                    'team_name' => $registration->team_name,
                    'institution' => $registration->institution,
                    'participants' => $registration->team_members,
                    'victory_points' => $totalVictoryPoints,
                    'average_score' => round($averageScore ?? 0, 2),
                    'matches_played' => $matchesPlayed,
                    'total_score' => $matchups->whereNotNull('team_score')->sum('team_score'),
                ];
            })
            ->sortByDesc(function($team) {
                return [$team['victory_points'], $team['average_score']];
            })
            ->values();

        return $leaderboard;
    }

    /**
     * Calculate overall leaderboard across all rounds for a competition
     *
     * @param Competition $competition
     * @return Collection
     */
    public function calculateOverallLeaderboard(Competition $competition): Collection
    {
        $registrations = $competition->registrations()
            ->where('status', 'confirmed')
            ->with(['teamMatchups.roundMatch.competitionRound'])
            ->get();

        $leaderboard = collect();

        foreach ($registrations as $registration) {
            $teamStats = $this->calculateTeamStats($registration);
            $leaderboard->push($teamStats);
        }

        return $leaderboard->sortByDesc(function($team) {
            return [$team['total_victory_points'], $team['average_score']];
        })->values();
    }

    /**
     * Calculate statistics for a specific team
     *
     * @param Registration $registration
     * @return array
     */
    private function calculateTeamStats(Registration $registration): array
    {
        $matchups = $registration->teamMatchups()
            ->with(['roundMatch.competitionRound'])
            ->get();

        $totalVictoryPoints = $matchups->sum('victory_points');
        $totalMatches = $matchups->count();
        $scoredMatches = $matchups->whereNotNull('team_score');
        $averageScore = $scoredMatches->avg('team_score');
        $totalScore = $scoredMatches->sum('team_score');

        // Calculate stats by round type
        $roundStats = $matchups->groupBy('roundMatch.competitionRound.round_type')
            ->map(function($roundMatchups, $roundType) {
                return [
                    'victory_points' => $roundMatchups->sum('victory_points'),
                    'matches' => $roundMatchups->count(),
                    'average_score' => round($roundMatchups->whereNotNull('team_score')->avg('team_score') ?? 0, 2),
                ];
            });

        return [
            'registration' => $registration,
            'team_name' => $registration->team_name,
            'institution' => $registration->institution,
            'participants' => $registration->team_members,
            'total_victory_points' => $totalVictoryPoints,
            'total_matches' => $totalMatches,
            'scored_matches' => $scoredMatches->count(),
            'average_score' => round($averageScore ?? 0, 2),
            'total_score' => $totalScore,
            'round_stats' => $roundStats,
        ];
    }

    /**
     * Get top teams for a competition (for homepage display)
     *
     * @param Competition $competition
     * @param int $limit
     * @return Collection
     */
    public function getTopTeams(Competition $competition, int $limit = 4): Collection
    {
        $leaderboard = $this->calculateOverallLeaderboard($competition);
        
        return $leaderboard->take($limit)->map(function($team, $index) {
            $team['rank'] = $index + 1;
            $team['rank_display'] = $this->getRankDisplay($index + 1);
            return $team;
        });
    }

    /**
     * Get rank display text
     *
     * @param int $rank
     * @return string
     */
    private function getRankDisplay(int $rank): string
    {
        switch ($rank) {
            case 1:
                return 'Juara 1';
            case 2:
                return 'Juara 2';
            case 3:
                return 'Juara 3';
            case 4:
                return 'Jury Mention';
            default:
                return "Rank {$rank}";
        }
    }

    /**
     * Calculate victory points for a match based on team scores
     *
     * @param Collection $teamMatchups
     * @return void
     */
    public function calculateMatchVictoryPoints(Collection $teamMatchups): void
    {
        // Sort teams by score (descending)
        $sortedTeams = $teamMatchups->whereNotNull('team_score')
            ->sortByDesc('team_score')
            ->values();

        // Assign victory points: 3, 2, 1, 0 for 4 teams
        $victoryPointsMap = [3, 2, 1, 0];

        foreach ($sortedTeams as $index => $matchup) {
            $matchup->update([
                'ranking' => $index + 1,
                'victory_points' => $victoryPointsMap[$index] ?? 0,
            ]);
        }
    }

    /**
     * Update competition leaderboard cache
     *
     * @param Competition $competition
     * @return void
     */
    public function updateCompetitionCache(Competition $competition): void
    {
        $leaderboard = $this->calculateOverallLeaderboard($competition);

        // Cache for 5 minutes
        Cache::put("leaderboard.competition.{$competition->id}", $leaderboard, now()->addMinutes(5));
    }

    /**
     * Get cached leaderboard or calculate if not cached
     *
     * @param Competition $competition
     * @return Collection
     */
    public function getCachedLeaderboard(Competition $competition): Collection
    {
        return Cache::remember(
            "leaderboard.competition.{$competition->id}",
            now()->addMinutes(5),
            fn() => $this->calculateOverallLeaderboard($competition)
        );
    }

    /**
     * Clear leaderboard cache for a competition
     *
     * @param Competition $competition
     * @return void
     */
    public function clearCompetitionCache(Competition $competition): void
    {
        Cache::forget("leaderboard.competition.{$competition->id}");
    }

    /**
     * Clear all leaderboard caches
     *
     * @return void
     */
    public function clearAllCaches(): void
    {
        Cache::flush(); // Or use tags if available
    }
}
