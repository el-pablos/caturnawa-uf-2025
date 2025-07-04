<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Submission;
use App\Models\Score;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
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
            $leaderboard = $this->getLeaderboard($selectedCompetition);
        }

        return view('public.leaderboard.index', compact('competitions', 'selectedCompetition', 'leaderboard'));
    }

    /**
     * Get leaderboard data for a competition
     */
    private function getLeaderboard(Competition $competition)
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
    public function getLeaderboardData(Competition $competition)
    {
        $leaderboard = $this->getLeaderboard($competition);
        
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
