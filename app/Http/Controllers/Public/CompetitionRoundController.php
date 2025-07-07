<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\RoundMatch;
use App\Models\TeamMatchup;
use Illuminate\Http\Request;

/**
 * Controller untuk halaman publik babak kompetisi
 *
 * Menangani tampilan babak kompetisi seperti struktur website pendahulu
 */
class CompetitionRoundController extends Controller
{
    /**
     * Tampilkan halaman utama kompetisi dengan daftar babak
     * Route: /matalomba/{competition}
     */
    public function show(Competition $competition)
    {
        $competition->load(['registrations.user', 'rounds' => function($query) {
            $query->active()->orderBy('round_type');
        }]);

        // Get confirmed registrations for participant list
        $registrations = $competition->registrations()
            ->where('status', 'confirmed')
            ->with(['user'])
            ->orderBy('created_at')
            ->get();

        return view('public.competition-rounds.show', compact('competition', 'registrations'));
    }

    /**
     * Tampilkan halaman babak tertentu dengan leaderboard
     * Route: /matalomba/{competition}/{round_type}
     */
    public function showRound(Competition $competition, $roundType)
    {
        $round = $competition->rounds()
            ->where('round_type', $roundType)
            ->with(['matches.teamMatchups.registration.user'])
            ->firstOrFail();

        // Calculate leaderboard for this round
        $leaderboard = $this->calculateLeaderboard($competition, $round);

        // Get matches for this round
        $matches = $round->matches()
            ->with(['teamMatchups.registration', 'teamMatchups.jury'])
            ->orderBy('match_name')
            ->get();

        return view('public.competition-rounds.round', compact('competition', 'round', 'leaderboard', 'matches'));
    }

    /**
     * Tampilkan detail pertandingan tertentu
     * Route: /matalomba/{competition}/{round_type}/{match_name}
     */
    public function showMatch(Competition $competition, $roundType, $matchName)
    {
        $round = $competition->rounds()
            ->where('round_type', $roundType)
            ->firstOrFail();

        $match = $round->matches()
            ->where('match_name', $matchName)
            ->with([
                'teamMatchups.registration.user',
                'teamMatchups.jury'
            ])
            ->firstOrFail();

        // Group team matchups by room/position for display
        $matchResults = $match->teamMatchups()
            ->with(['registration', 'jury'])
            ->orderBy('ranking')
            ->get()
            ->groupBy(function($matchup) {
                return $matchup->roundMatch->room_name ?? 'Main Room';
            });

        return view('public.competition-rounds.match', compact('competition', 'round', 'match', 'matchResults'));
    }

    /**
     * Calculate leaderboard for a specific round
     */
    private function calculateLeaderboard(Competition $competition, CompetitionRound $round)
    {
        // Get all team matchups for this round
        $teamMatchups = TeamMatchup::whereHas('roundMatch', function($query) use ($round) {
            $query->where('competition_round_id', $round->id);
        })
        ->with(['registration.user'])
        ->get();

        // Group by team and calculate total victory points
        $leaderboard = $teamMatchups->groupBy('registration_id')
            ->map(function($matchups) {
                $registration = $matchups->first()->registration;
                $totalVictoryPoints = $matchups->sum('victory_points');
                $totalScore = $matchups->avg('team_score');

                return [
                    'registration' => $registration,
                    'team_name' => $registration->team_name,
                    'participants' => $registration->team_members,
                    'victory_points' => $totalVictoryPoints,
                    'average_score' => round($totalScore, 2),
                    'matches_played' => $matchups->count(),
                ];
            })
            ->sortByDesc('victory_points')
            ->values();

        return $leaderboard;
    }
}
