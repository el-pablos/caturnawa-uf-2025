<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Submission;
use App\Models\Score;
use App\Services\LeaderboardService;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    protected $leaderboardService;

    public function __construct(LeaderboardService $leaderboardService)
    {
        $this->leaderboardService = $leaderboardService;
    }

    /**
     * Display leaderboard page
     */
    public function index(Request $request)
    {
        $competitions = Competition::where('is_active', true)
            ->where('show_leaderboard', true)
            ->orderBy('name')
            ->get();

        $selectedCompetition = null;
        $leaderboard = collect();

        if ($request->filled('competition') && $competitions->count() > 0) {
            $selectedCompetition = $competitions->where('id', $request->competition)->first();
        }

        if (!$selectedCompetition && $competitions->count() > 0) {
            $selectedCompetition = $competitions->first();
        }

        if ($selectedCompetition) {
            $leaderboard = $this->getLeaderboardData($selectedCompetition);
        }

        return view('public.leaderboard.index', compact('competitions', 'selectedCompetition', 'leaderboard'));
    }

    /**
     * Get leaderboard data for a competition (prioritizes LeaderboardEntry over calculated data)
     */
    private function getLeaderboardData(Competition $competition)
    {
        // First, try to get data from LeaderboardEntry (seeded data for display)
        $leaderboardEntries = \App\Models\LeaderboardEntry::where('competition_id', $competition->id)
            ->where('is_active', true)
            ->orderBy('rank')
            ->get();

        if ($leaderboardEntries->count() > 0) {
            // Convert LeaderboardEntry data to the format expected by the view
            return $leaderboardEntries->map(function ($entry, $index) {
                return [
                    'rank' => $entry->rank,
                    'participant_name' => $entry->participant_name,
                    'team_name' => $entry->team_name,
                    'institution' => $entry->institution,
                    'submission_title' => $entry->team_name, // Use team name as submission title for display
                    'average_score' => $entry->score,
                    'total_juries' => 3, // Default value for display
                    'scores_detail' => [], // Empty for seeded data
                ];
            });
        }

        // Fallback to calculated leaderboard from submissions
        return $this->getCalculatedLeaderboard($competition);
    }

    /**
     * Get calculated leaderboard data from submissions (original method)
     */
    private function getCalculatedLeaderboard(Competition $competition)
    {
        // Get submissions with final scores
        $submissions = Submission::with(['registration.user', 'scores.jury'])
            ->whereHas('registration', function ($q) use ($competition) {
                $q->where('competition_id', $competition->id)
                  ->where('status', 'confirmed');
            })
            ->where('status', 'submitted')
            ->get();

        $leaderboard = $submissions->map(function ($submission) {
            // Calculate average score from final scores only
            $finalScores = $submission->scores->where('is_final', true);
            
            if ($finalScores->count() === 0) {
                return null; // Skip submissions without final scores
            }

            $averageScore = $finalScores->avg('total_score');
            $totalJuries = $finalScores->count();

            return [
                'submission' => $submission,
                'participant_name' => $submission->registration->user->name,
                'team_name' => $submission->registration->team_name,
                'submission_title' => $submission->title,
                'average_score' => round($averageScore, 2),
                'total_juries' => $totalJuries,
                'institution' => $submission->registration->user->institution,
                'scores_detail' => $finalScores->map(function ($score) {
                    return [
                        'jury_name' => $score->jury->name ?? 'Unknown',
                        'score' => $score->total_score,
                        'comments' => $score->comments
                    ];
                })->toArray()
            ];
        })->filter()->sortByDesc('average_score')->values();

        // Add ranking
        $leaderboard = $leaderboard->map(function ($item, $index) {
            $item['rank'] = $index + 1;
            return $item;
        });

        return $leaderboard;
    }

    /**
     * Get leaderboard data as JSON (for AJAX)
     */
    public function getLeaderboardDataJson(Competition $competition)
    {
        $leaderboard = $this->getLeaderboardData($competition);
        
        return response()->json([
            'success' => true,
            'data' => $leaderboard,
            'competition' => [
                'id' => $competition->id,
                'name' => $competition->name,
                'category' => $competition->category,
                'total_participants' => $leaderboard->count()
            ]
        ]);
    }
}
