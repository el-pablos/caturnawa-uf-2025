<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaderboardEntry;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LeaderboardController extends Controller
{
    /**
     * Get leaderboard data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $competitionId = $request->get('competition_id');
        $page = $request->get('page', 1);
        $perPage = min($request->get('per_page', 10), 50); // Max 50 per page

        // Create cache key
        $cacheKey = "leaderboard_" . ($competitionId ?: 'all') . "_page_{$page}_per_{$perPage}";

        // Cache for 5 minutes
        $data = Cache::remember($cacheKey, 300, function () use ($competitionId, $page, $perPage) {
            return $this->getLeaderboardData($competitionId, $page, $perPage);
        });

        return response()->json([
            'success' => true,
            'data' => $data['entries'],
            'pagination' => $data['pagination'],
            'competition' => $data['competition'],
            'cached_at' => now()->toISOString(),
        ]);
    }

    /**
     * Get leaderboard data with pagination
     */
    private function getLeaderboardData($competitionId, $page, $perPage)
    {
        $query = LeaderboardEntry::active()
            ->with(['competition', 'registration.user'])
            ->orderBy('rank');

        if ($competitionId) {
            $query->byCompetition($competitionId);
        }

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);

        $entries = $paginated->items();
        $formattedEntries = collect($entries)->map(function ($entry) {
            return [
                'id' => $entry->id,
                'rank' => $entry->rank,
                'rank_type' => $entry->rank_type,
                'team_name' => $entry->team_name,
                'participant_name' => $entry->participant_name,
                'institution' => $entry->institution,
                'score' => $entry->score,
                'victory_points' => $entry->victory_points,
                'competition' => [
                    'id' => $entry->competition->id,
                    'name' => $entry->competition->name,
                    'category' => $entry->competition->category,
                ],
                'computed_at' => $entry->computed_at->toISOString(),
            ];
        });

        $competition = null;
        if ($competitionId) {
            $competition = Competition::find($competitionId);
            if ($competition) {
                $competition = [
                    'id' => $competition->id,
                    'name' => $competition->name,
                    'category' => $competition->category,
                    'description' => $competition->description,
                ];
            }
        }

        return [
            'entries' => $formattedEntries,
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'from' => $paginated->firstItem(),
                'to' => $paginated->lastItem(),
                'has_more_pages' => $paginated->hasMorePages(),
            ],
            'competition' => $competition,
        ];
    }

    /**
     * Update leaderboard for specific competition
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'competition_id' => 'required|exists:competitions,id',
        ]);

        $competitionId = $request->competition_id;

        try {
            LeaderboardEntry::updateLeaderboard($competitionId);

            // Clear related cache
            $this->clearLeaderboardCache($competitionId);

            return response()->json([
                'success' => true,
                'message' => 'Leaderboard updated successfully',
                'competition_id' => $competitionId,
                'updated_at' => now()->toISOString(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update leaderboard',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear leaderboard cache
     */
    private function clearLeaderboardCache($competitionId = null)
    {
        $patterns = [
            "leaderboard_all_*",
        ];

        if ($competitionId) {
            $patterns[] = "leaderboard_{$competitionId}_*";
        }

        foreach ($patterns as $pattern) {
            Cache::flush(); // Simple approach, in production use more specific cache clearing
        }
    }
}
