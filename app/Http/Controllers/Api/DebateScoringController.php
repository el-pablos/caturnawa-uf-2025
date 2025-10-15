<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DebateMatch;
use App\Services\DebateScoringService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DebateScoringController extends Controller
{
    protected DebateScoringService $scoringService;

    public function __construct(DebateScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    /**
     * Get judge's assigned matches
     */
    public function getMatches(Request $request): JsonResponse
    {
        $stage = $request->get('stage');
        $roundNumber = $request->get('round_number');

        $matches = $this->scoringService->getJudgeMatches(
            auth()->id(),
            $stage,
            $roundNumber
        );

        return response()->json([
            'success' => true,
            'data' => $matches,
        ]);
    }

    /**
     * Get specific match details
     */
    public function getMatch($matchId): JsonResponse
    {
        $match = DebateMatch::with([
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
        ])->findOrFail($matchId);

        // Check if judge is assigned to this match
        if ($match->judge_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
                'message' => 'You are not assigned to this match',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $match,
        ]);
    }

    /**
     * Submit scores for a match
     */
    public function submitScores(Request $request, $matchId): JsonResponse
    {
        $request->validate([
            'team1.speaker1' => 'required|numeric|min:70|max:80',
            'team1.speaker2' => 'required|numeric|min:70|max:80',
            'team1.teamScore' => 'required|numeric',
            'team2.speaker1' => 'required|numeric|min:70|max:80',
            'team2.speaker2' => 'required|numeric|min:70|max:80',
            'team2.teamScore' => 'required|numeric',
            'team3.speaker1' => 'required|numeric|min:70|max:80',
            'team3.speaker2' => 'required|numeric|min:70|max:80',
            'team3.teamScore' => 'required|numeric',
            'team4.speaker1' => 'required|numeric|min:70|max:80',
            'team4.speaker2' => 'required|numeric|min:70|max:80',
            'team4.teamScore' => 'required|numeric',
            'ranking' => 'required|array|size:4',
            'ranking.*' => 'required|integer|min:0|max:3',
        ]);

        try {
            $match = DebateMatch::with('round')->findOrFail($matchId);

            // Check if judge is assigned to this match
            if ($match->judge_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized',
                    'message' => 'You are not assigned to this match',
                ], 403);
            }

            // Check if round is frozen
            if ($match->round->isFrozen()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Round frozen',
                    'message' => 'This round has been frozen. Scores cannot be modified.',
                ], 403);
            }

            $scores = [
                'team1' => $request->input('team1'),
                'team2' => $request->input('team2'),
                'team3' => $request->input('team3'),
                'team4' => $request->input('team4'),
                'ranking' => $request->input('ranking'),
            ];

            // Validate scores
            $validation = $this->scoringService->validateBPScores($scores);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid scores',
                    'message' => implode(', ', $validation['errors']),
                    'errors' => $validation['errors'],
                ], 422);
            }

            // Submit scores
            $result = $this->scoringService->submitJudgeScores(
                $matchId,
                auth()->id(),
                $scores
            );

            return response()->json([
                'success' => true,
                'message' => 'Scores submitted successfully',
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to submit scores',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get scores for a match
     */
    public function getScores($matchId): JsonResponse
    {
        try {
            $match = DebateMatch::findOrFail($matchId);

            // Check if judge is assigned to this match
            if ($match->judge_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized',
                    'message' => 'You are not assigned to this match',
                ], 403);
            }

            $scoreData = $this->scoringService->getMatchScores($matchId);

            return response()->json([
                'success' => true,
                'data' => $scoreData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch scores',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

