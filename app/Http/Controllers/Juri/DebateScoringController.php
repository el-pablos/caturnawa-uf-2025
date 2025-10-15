<?php

namespace App\Http\Controllers\Juri;

use App\Http\Controllers\Controller;
use App\Models\DebateMatch;
use App\Models\DebateRound;
use App\Services\DebateScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebateScoringController extends Controller
{
    protected DebateScoringService $scoringService;

    public function __construct(DebateScoringService $scoringService)
    {
        $this->middleware(['auth', 'role:judge']);
        $this->scoringService = $scoringService;
    }

    /**
     * Display judge's assigned matches
     */
    public function index(Request $request)
    {
        $stage = $request->get('stage');
        $roundNumber = $request->get('round_number');

        $matches = $this->scoringService->getJudgeMatches(
            auth()->id(),
            $stage,
            $roundNumber
        );

        return view('juri.debate.index', compact('matches', 'stage', 'roundNumber'));
    }

    /**
     * Show scoring form for a specific match
     */
    public function scoreMatch($matchId)
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
            return redirect()
                ->route('juri.debate.index')
                ->with('error', 'You are not assigned to this match.');
        }

        // Check if round is frozen
        if ($match->round->isFrozen()) {
            return redirect()
                ->route('juri.debate.index')
                ->with('error', 'This round has been frozen. Scores cannot be modified.');
        }

        // Get existing scores if any
        $existingScores = $this->getExistingScores($match);

        return view('juri.debate.score', compact('match', 'existingScores'));
    }

    /**
     * Submit scores for a match
     */
    public function submitScores(Request $request, $matchId)
    {
        // Validate the request
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
                return redirect()
                    ->route('juri.debate.index')
                    ->with('error', 'You are not assigned to this match.');
            }

            // Check if round is frozen
            if ($match->round->isFrozen()) {
                return redirect()
                    ->route('juri.debate.index')
                    ->with('error', 'This round has been frozen. Scores cannot be modified.');
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
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Invalid scores: ' . implode(', ', $validation['errors']));
            }

            // Submit scores
            $result = $this->scoringService->submitJudgeScores(
                $matchId,
                auth()->id(),
                $scores
            );

            Log::info('Judge submitted scores', [
                'judge_id' => auth()->id(),
                'match_id' => $matchId,
                'scores_created' => $result['scores_created'],
            ]);

            return redirect()
                ->route('juri.debate.index')
                ->with('success', 'Scores submitted successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to submit scores', [
                'judge_id' => auth()->id(),
                'match_id' => $matchId,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to submit scores: ' . $e->getMessage());
        }
    }

    /**
     * View submitted scores for a match
     */
    public function viewScores($matchId)
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
            'scores.teamMember.participant'
        ])->findOrFail($matchId);

        // Check if judge is assigned to this match
        if ($match->judge_id !== auth()->id()) {
            return redirect()
                ->route('juri.debate.index')
                ->with('error', 'You are not assigned to this match.');
        }

        $scoreData = $this->scoringService->getMatchScores($matchId);

        return view('juri.debate.view-scores', compact('match', 'scoreData'));
    }

    /**
     * Get existing scores for a match (for editing)
     */
    protected function getExistingScores(DebateMatch $match): ?array
    {
        if ($match->scores->isEmpty()) {
            return null;
        }

        $scores = [
            'team1' => ['speaker1' => 0, 'speaker2' => 0, 'teamScore' => 0],
            'team2' => ['speaker1' => 0, 'speaker2' => 0, 'teamScore' => 0],
            'team3' => ['speaker1' => 0, 'speaker2' => 0, 'teamScore' => 0],
            'team4' => ['speaker1' => 0, 'speaker2' => 0, 'teamScore' => 0],
            'ranking' => [],
        ];

        $positions = ['OG' => 'team1', 'OO' => 'team2', 'CG' => 'team3', 'CO' => 'team4'];

        foreach ($match->scores as $score) {
            $teamKey = $positions[$score->team_position] ?? null;
            if (!$teamKey) continue;

            // Determine speaker number based on BP position
            $speakerNum = in_array($score->bp_position, ['PM', 'LO', 'MG', 'MO']) ? 1 : 2;
            $scores[$teamKey]["speaker{$speakerNum}"] = $score->score;
            $scores[$teamKey]['teamScore'] += $score->score;
        }

        // Reconstruct ranking
        $teamRanks = [
            $match->first_place_team_id,
            $match->second_place_team_id,
            $match->third_place_team_id,
            $match->fourth_place_team_id,
        ];

        $teams = [$match->team1_id, $match->team2_id, $match->team3_id, $match->team4_id];
        
        foreach ($teamRanks as $rankedTeamId) {
            $scores['ranking'][] = array_search($rankedTeamId, $teams);
        }

        return $scores;
    }
}

