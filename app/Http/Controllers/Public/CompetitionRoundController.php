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
     * Tampilkan daftar semua kompetisi dengan babak
     * Route: /matalomba
     */
    public function index()
    {
        $competitions = Competition::active()
            ->with(['rounds' => function($query) {
                $query->orderBy('round_type');
            }])
            ->orderBy('name')
            ->get();

        return view('public.competition-rounds.index', compact('competitions'));
    }

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

        // If matchName looks like a team name, show team detail instead
        if (str_contains($matchName, ' ') || str_contains($matchName, '%20')) {
            return $this->showTeamDetail($competition, $round, urldecode($matchName));
        }

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
     * Tampilkan detail tim tertentu dalam babak
     * Route: /matalomba/{competition}/{round_type}/detail{team_name}
     */
    public function showTeamDetail(Competition $competition, $round, $teamName)
    {
        // Decode URL-encoded team name
        $teamName = urldecode($teamName);
        
        // Remove 'detail' prefix if present
        if (str_starts_with($teamName, 'detail')) {
            $teamName = substr($teamName, 6);
        }

        // Find the registration (team) by name
        $registration = $competition->registrations()
            ->where('status', 'confirmed')
            ->where(function($query) use ($teamName) {
                $query->where('team_name', 'LIKE', '%' . $teamName . '%')
                      ->orWhere('team_name', $teamName);
            })
            ->with(['user', 'teamMembers'])
            ->first();

        if (!$registration) {
            abort(404, 'Team not found');
        }

        // Get scores for this team in this round
        $scores = Score::where('competition_id', $competition->id)
            ->where('registration_id', $registration->id)
            ->with(['jury'])
            ->get();

        // Get juries who have scored this team
        $juries = $scores->pluck('jury')->filter()->unique('id');

        // Calculate total score based on competition type
        $totalScore = $this->calculateTeamTotalScore($scores, $competition);

        return view('public.competition-rounds.team-detail', compact(
            'competition', 
            'round', 
            'registration',
            'scores',
            'juries',
            'totalScore'
        ));
    }

    /**
     * Calculate leaderboard for a specific round
     */
    private function calculateLeaderboard(Competition $competition, CompetitionRound $round)
    {
        // For now, get basic leaderboard from registrations and their scores
        $registrations = $competition->registrations()
            ->where('status', 'confirmed')
            ->with(['user', 'teamMembers'])
            ->get();

        $leaderboard = $registrations->map(function($registration) use ($competition, $round) {
            // Get scores for this team
            $scores = Score::where('competition_id', $competition->id)
                ->where('registration_id', $registration->id)
                ->get();
            
            $totalScore = $this->calculateTeamTotalScore($scores, $competition);
            
            return [
                'registration' => $registration,
                'team_name' => $registration->team_name ?: $registration->user->name,
                'participants' => $registration->teamMembers,
                'victory_points' => 0, // Will be implemented later
                'average_score' => round($totalScore, 2),
                'matches_played' => 0,
                'total_score' => $totalScore,
            ];
        })
        ->sortByDesc('total_score')
        ->values();

        return $leaderboard;
    }

    /**
     * Calculate total score untuk tim berdasarkan tipe kompetisi
     */
    private function calculateTeamTotalScore($scores, Competition $competition)
    {
        if ($scores->isEmpty()) {
            return 0;
        }

        if ($competition->isSpcCompetition()) {
            return $this->calculateSpcTeamScore($scores);
        } elseif ($competition->isEdcCompetition() || $competition->isKdbiCompetition()) {
            return $this->calculateDebateTeamScore($scores);
        }

        // Default: average of all scores
        return $scores->avg('total_score') ?? 0;
    }

    /**
     * Calculate SPC score berdasarkan bobot 60% naskah, 40% presentasi
     */
    private function calculateSpcTeamScore($scores)
    {
        $naskahScores = $scores->filter(function($score) {
            return isset($score->criteria_scores['naskah']) || 
                   str_contains(strtolower($score->comments ?? ''), 'naskah');
        });
        
        $presentasiScores = $scores->filter(function($score) {
            return isset($score->criteria_scores['presentasi']) || 
                   str_contains(strtolower($score->comments ?? ''), 'presentasi');
        });

        $avgNaskah = $naskahScores->avg('total_score') ?? 0;
        $avgPresentasi = $presentasiScores->avg('total_score') ?? 0;

        return ($avgNaskah * 0.6) + ($avgPresentasi * 0.4);
    }

    /**
     * Calculate Debate score (average dari semua scores)
     */
    private function calculateDebateTeamScore($scores)
    {
        return $scores->avg('total_score') ?? 0;
    }

    /**
     * Show final results for competition (combining all rounds)
     * Route: /matalomba/{competition}/final
     */
    public function showFinalResults(Competition $competition)
    {
        // Get all registrations
        $registrations = $competition->registrations()
            ->where('status', 'confirmed')
            ->with(['user', 'teamMembers'])
            ->get();

        // Calculate final scores for all teams
        $finalResults = $registrations->map(function($registration) use ($competition) {
            // Get all scores for this team across all rounds
            $scores = Score::where('competition_id', $competition->id)
                ->where('registration_id', $registration->id)
                ->where('is_final', true)
                ->get();
            
            $totalScore = $this->calculateTeamTotalScore($scores, $competition);
            
            return [
                'registration' => $registration,
                'team_name' => $registration->team_name ?: $registration->user->name,
                'participants' => $registration->teamMembers,
                'total_score' => $totalScore,
                'scores_count' => $scores->count(),
                'average_score' => round($totalScore, 2),
                'grade' => $this->getGradeForScore($totalScore, $competition),
            ];
        })
        ->sortByDesc('total_score')
        ->values();

        // Add ranking
        foreach ($finalResults as $index => $result) {
            $finalResults[$index]['rank'] = $index + 1;
        }

        return view('public.competition-rounds.final-results', compact('competition', 'finalResults'));
    }

    /**
     * Get grade based on score and competition type
     */
    private function getGradeForScore($score, Competition $competition)
    {
        if ($competition->isSpcCompetition()) {
            if ($score >= 90) return 'A';
            if ($score >= 80) return 'B+';
            if ($score >= 70) return 'B';
            if ($score >= 60) return 'C+';
            if ($score >= 50) return 'C';
            return 'D';
        } elseif ($competition->isEdcCompetition() || $competition->isKdbiCompetition()) {
            if ($score >= 96) return 'A+';
            if ($score >= 91) return 'A';
            if ($score >= 86) return 'A-';
            if ($score >= 81) return 'B+';
            if ($score >= 76) return 'B';
            if ($score >= 71) return 'B-';
            if ($score >= 66) return 'C+';
            if ($score >= 61) return 'C';
            if ($score >= 56) return 'C-';
            if ($score >= 50) return 'D';
            return 'F';
        }
        
        // Default grading
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }
}
