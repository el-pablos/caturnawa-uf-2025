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
     * Sorted by victory_points (higher is better)
     */
    private function getLeaderboardData(Competition $competition)
    {
        // First, try to get data from LeaderboardEntry (seeded data for display)
        $leaderboardEntries = \App\Models\LeaderboardEntry::where('competition_id', $competition->id)
            ->where('is_active', true)
            ->orderByDesc('victory_points') // Sort by victory points (higher is better)
            ->orderBy('rank')
            ->get();

        if ($leaderboardEntries->count() > 0) {
            // Convert LeaderboardEntry data to the format expected by the view
            // Re-rank based on victory_points
            $rank = 1;
            return $leaderboardEntries->map(function ($entry) use (&$rank) {
                return [
                    'rank' => $rank++,
                    'participant_name' => $entry->participant_name,
                    'team_name' => $entry->team_name,
                    'institution' => $entry->institution,
                    'submission_title' => $entry->team_name, // Use team name as submission title for display
                    'average_score' => $entry->score,
                    'victory_points' => $entry->victory_points,
                    'total_juries' => 4, // Default value for display (4 juries)
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
                  ->whereIn('status', ['confirmed', 'paid']); // Include paid status
            })
            ->where('is_final', true) // Use is_final instead of status
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

    /**
     * Export leaderboard to CSV
     */
    public function exportCsv(Competition $competition)
    {
        $leaderboard = $this->getLeaderboardData($competition);

        $filename = 'leaderboard_' . str_replace(' ', '_', $competition->name) . '_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($leaderboard) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, ['Rank', 'Participant Name', 'Team Name', 'Institution', 'Average Score', 'Total Juries']);

            // Data rows
            foreach ($leaderboard as $item) {
                fputcsv($file, [
                    $item['rank'],
                    $item['participant_name'] ?? '',
                    $item['team_name'] ?? '',
                    $item['institution'] ?? '',
                    $item['average_score'] ?? 0,
                    $item['total_juries'] ?? 0,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
