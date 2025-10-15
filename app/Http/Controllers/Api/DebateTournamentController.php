<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\DebateRound;
use App\Models\DebateMatch;
use App\Services\TournamentGenerationService;
use App\Services\StandingsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DebateTournamentController extends Controller
{
    protected TournamentGenerationService $tournamentService;
    protected StandingsService $standingsService;

    public function __construct(
        TournamentGenerationService $tournamentService,
        StandingsService $standingsService
    ) {
        $this->tournamentService = $tournamentService;
        $this->standingsService = $standingsService;
    }

    /**
     * Generate tournament structure
     */
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'competition_type' => 'required|in:KDBI,EDC',
        ]);

        try {
            $competition = Competition::where('type', $request->competition_type)->firstOrFail();
            $result = $this->tournamentService->generateTournament($competition);

            return response()->json([
                'success' => true,
                'message' => 'Tournament generated successfully',
                'data' => [
                    'competition' => [
                        'id' => $competition->id,
                        'name' => $competition->name,
                        'type' => $competition->type,
                    ],
                    'teams_count' => $result['teams_count'],
                    'rounds_created' => count($result['rounds']),
                    'matches_created' => count($result['matches']),
                    'rounds' => collect($result['rounds'])->map(fn($r) => [
                        'id' => $r->id,
                        'stage' => $r->stage,
                        'round_name' => $r->round_name,
                    ]),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate tournament',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get tournament status
     */
    public function status(Request $request): JsonResponse
    {
        $request->validate([
            'competition_type' => 'required|in:KDBI,EDC',
        ]);

        try {
            $competition = Competition::where('type', $request->competition_type)->firstOrFail();
            $status = $this->tournamentService->getTournamentStatus($competition);

            return response()->json([
                'success' => true,
                'data' => $status,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch tournament status',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all rounds
     */
    public function rounds(Request $request): JsonResponse
    {
        $competitionType = $request->get('competition_type', 'KDBI');
        
        $competition = Competition::where('type', $competitionType)->first();
        if (!$competition) {
            return response()->json(['error' => 'Competition not found'], 404);
        }

        $rounds = DebateRound::where('competition_id', $competition->id)
            ->with('matches')
            ->orderBy('stage')
            ->orderBy('round_number')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rounds,
        ]);
    }

    /**
     * Get specific round details
     */
    public function showRound($roundId): JsonResponse
    {
        $round = DebateRound::with([
            'competition',
            'matches.team1',
            'matches.team2',
            'matches.team3',
            'matches.team4',
            'matches.judge',
            'matches.scores'
        ])->findOrFail($roundId);

        return response()->json([
            'success' => true,
            'data' => $round,
        ]);
    }

    /**
     * Freeze a round
     */
    public function freezeRound($roundId): JsonResponse
    {
        try {
            $round = DebateRound::findOrFail($roundId);
            $round->freeze(auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Round frozen successfully',
                'data' => $round,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to freeze round',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Unfreeze a round
     */
    public function unfreezeRound($roundId): JsonResponse
    {
        try {
            $round = DebateRound::findOrFail($roundId);
            $round->unfreeze();

            return response()->json([
                'success' => true,
                'message' => 'Round unfrozen successfully',
                'data' => $round,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to unfreeze round',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all matches
     */
    public function matches(Request $request): JsonResponse
    {
        $query = DebateMatch::with([
            'round',
            'team1',
            'team2',
            'team3',
            'team4',
            'judge'
        ]);

        if ($request->has('round_id')) {
            $query->where('round_id', $request->round_id);
        }

        if ($request->has('completed')) {
            if ($request->completed === 'true') {
                $query->completed();
            } else {
                $query->pending();
            }
        }

        $matches = $query->orderBy('match_number')->get();

        return response()->json([
            'success' => true,
            'data' => $matches,
        ]);
    }

    /**
     * Get specific match details
     */
    public function showMatch($matchId): JsonResponse
    {
        $match = DebateMatch::with([
            'round.competition',
            'team1.teamMembers.participant',
            'team2.teamMembers.participant',
            'team3.teamMembers.participant',
            'team4.teamMembers.participant',
            'judge',
            'scores.teamMember.participant'
        ])->findOrFail($matchId);

        return response()->json([
            'success' => true,
            'data' => $match,
        ]);
    }

    /**
     * Update match details
     */
    public function updateMatch(Request $request, $matchId): JsonResponse
    {
        $request->validate([
            'room_name' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
        ]);

        try {
            $match = DebateMatch::findOrFail($matchId);
            $match->update($request->only(['room_name', 'scheduled_at']));

            return response()->json([
                'success' => true,
                'message' => 'Match updated successfully',
                'data' => $match,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update match',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Assign judge to match
     */
    public function assignJudge(Request $request, $matchId): JsonResponse
    {
        $request->validate([
            'judge_id' => 'required|exists:users,id',
        ]);

        try {
            $match = DebateMatch::findOrFail($matchId);
            $match->update(['judge_id' => $request->judge_id]);

            return response()->json([
                'success' => true,
                'message' => 'Judge assigned successfully',
                'data' => $match->load('judge'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to assign judge',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get standings
     */
    public function standings(Request $request): JsonResponse
    {
        $competitionType = $request->get('competition_type', 'KDBI');
        $stage = $request->get('stage');

        $competition = Competition::where('type', $competitionType)->firstOrFail();

        if ($stage) {
            $standings = $this->standingsService->getStandingsByStage($competition, $stage);
        } else {
            $standings = $this->standingsService->getStandings($competition);
        }

        return response()->json([
            'success' => true,
            'data' => $standings,
        ]);
    }

    /**
     * Recalculate standings
     */
    public function recalculateStandings(Request $request): JsonResponse
    {
        $request->validate([
            'competition_type' => 'required|in:KDBI,EDC',
        ]);

        try {
            $competition = Competition::where('type', $request->competition_type)->firstOrFail();
            $result = $this->standingsService->recalculateStandings($competition);

            return response()->json([
                'success' => true,
                'message' => 'Standings recalculated successfully',
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to recalculate standings',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export standings
     */
    public function exportStandings(Request $request): JsonResponse
    {
        $competitionType = $request->get('competition_type', 'KDBI');
        $competition = Competition::where('type', $competitionType)->firstOrFail();

        $standings = $this->standingsService->exportStandings($competition);

        return response()->json([
            'success' => true,
            'data' => $standings,
        ]);
    }

    /**
     * Get speaker standings
     */
    public function speakerStandings(Request $request): JsonResponse
    {
        $competitionType = $request->get('competition_type', 'KDBI');
        $competition = Competition::where('type', $competitionType)->firstOrFail();

        $speakerStandings = $this->standingsService->getSpeakerStandings($competition);

        return response()->json([
            'success' => true,
            'data' => $speakerStandings,
        ]);
    }
}

