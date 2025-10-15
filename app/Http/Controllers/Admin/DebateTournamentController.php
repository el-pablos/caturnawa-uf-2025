<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\DebateRound;
use App\Models\DebateMatch;
use App\Services\TournamentGenerationService;
use App\Services\StandingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebateTournamentController extends Controller
{
    protected TournamentGenerationService $tournamentService;
    protected StandingsService $standingsService;

    public function __construct(
        TournamentGenerationService $tournamentService,
        StandingsService $standingsService
    ) {
        $this->middleware(['auth', 'role:admin']);
        $this->tournamentService = $tournamentService;
        $this->standingsService = $standingsService;
    }

    /**
     * Display tournament management page
     */
    public function index(Request $request)
    {
        $competitionType = $request->get('competition_type', 'KDBI');
        
        $competition = Competition::where('type', $competitionType)->first();
        
        if (!$competition) {
            return redirect()->back()->with('error', 'Competition not found');
        }

        $status = $this->tournamentService->getTournamentStatus($competition);
        $standings = $this->standingsService->getStandings($competition);

        return view('admin.debate.tournament.index', compact(
            'competition',
            'status',
            'standings'
        ));
    }

    /**
     * Generate tournament structure
     */
    public function generate(Request $request)
    {
        $request->validate([
            'competition_type' => 'required|in:KDBI,EDC',
        ]);

        try {
            $competition = Competition::where('type', $request->competition_type)->firstOrFail();

            $result = $this->tournamentService->generateTournament($competition);

            Log::info("Tournament generated for {$request->competition_type}", [
                'rounds' => count($result['rounds']),
                'matches' => count($result['matches']),
                'teams' => $result['teams_count'],
            ]);

            return redirect()
                ->route('admin.debate.tournament.index', ['competition_type' => $request->competition_type])
                ->with('success', "Tournament generated successfully! Created {$result['teams_count']} teams, " . 
                    count($result['rounds']) . " rounds, and " . count($result['matches']) . " matches.");

        } catch (\Exception $e) {
            Log::error('Tournament generation failed', [
                'error' => $e->getMessage(),
                'competition_type' => $request->competition_type,
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to generate tournament: ' . $e->getMessage());
        }
    }

    /**
     * View specific round details
     */
    public function showRound(Request $request, $roundId)
    {
        $round = DebateRound::with([
            'competition',
            'matches.team1.participant',
            'matches.team1.teamMembers.participant',
            'matches.team2.participant',
            'matches.team2.teamMembers.participant',
            'matches.team3.participant',
            'matches.team3.teamMembers.participant',
            'matches.team4.participant',
            'matches.team4.teamMembers.participant',
            'matches.judge',
            'matches.scores'
        ])->findOrFail($roundId);

        return view('admin.debate.tournament.round', compact('round'));
    }

    /**
     * Freeze a round (prevent further changes)
     */
    public function freezeRound(Request $request, $roundId)
    {
        try {
            $round = DebateRound::findOrFail($roundId);
            $round->freeze(auth()->id());

            return redirect()
                ->back()
                ->with('success', "Round '{$round->round_name}' has been frozen.");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to freeze round: ' . $e->getMessage());
        }
    }

    /**
     * Unfreeze a round
     */
    public function unfreezeRound(Request $request, $roundId)
    {
        try {
            $round = DebateRound::findOrFail($roundId);
            $round->unfreeze();

            return redirect()
                ->back()
                ->with('success', "Round '{$round->round_name}' has been unfrozen.");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to unfreeze round: ' . $e->getMessage());
        }
    }

    /**
     * Assign judge to a match
     */
    public function assignJudge(Request $request, $matchId)
    {
        $request->validate([
            'judge_id' => 'required|exists:users,id',
        ]);

        try {
            $match = DebateMatch::findOrFail($matchId);
            $match->update(['judge_id' => $request->judge_id]);

            return redirect()
                ->back()
                ->with('success', 'Judge assigned successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to assign judge: ' . $e->getMessage());
        }
    }

    /**
     * Update match details
     */
    public function updateMatch(Request $request, $matchId)
    {
        $request->validate([
            'room_name' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
        ]);

        try {
            $match = DebateMatch::findOrFail($matchId);
            $match->update($request->only(['room_name', 'scheduled_at']));

            return redirect()
                ->back()
                ->with('success', 'Match updated successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to update match: ' . $e->getMessage());
        }
    }

    /**
     * View standings
     */
    public function standings(Request $request)
    {
        $competitionType = $request->get('competition_type', 'KDBI');
        $stage = $request->get('stage');

        $competition = Competition::where('type', $competitionType)->firstOrFail();

        if ($stage) {
            $standings = $this->standingsService->getStandingsByStage($competition, $stage);
        } else {
            $standings = $this->standingsService->getStandings($competition);
        }

        $speakerStandings = $this->standingsService->getSpeakerStandings($competition);

        return view('admin.debate.tournament.standings', compact(
            'competition',
            'standings',
            'speakerStandings',
            'stage'
        ));
    }

    /**
     * Recalculate all standings
     */
    public function recalculateStandings(Request $request)
    {
        $request->validate([
            'competition_type' => 'required|in:KDBI,EDC',
        ]);

        try {
            $competition = Competition::where('type', $request->competition_type)->firstOrFail();
            $result = $this->standingsService->recalculateStandings($competition);

            return redirect()
                ->back()
                ->with('success', "Recalculated standings for {$result['teams_recalculated']} teams.");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to recalculate standings: ' . $e->getMessage());
        }
    }

    /**
     * Export standings to CSV
     */
    public function exportStandings(Request $request)
    {
        $competitionType = $request->get('competition_type', 'KDBI');
        $competition = Competition::where('type', $competitionType)->firstOrFail();

        $standings = $this->standingsService->exportStandings($competition);

        $filename = strtolower($competitionType) . '_standings_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($standings) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Rank',
                'Team Name',
                'Institution',
                'Matches Played',
                'Team Points',
                'Speaker Points',
                'Avg Speaker Points',
                'Avg Position',
                '1st Places',
                '2nd Places',
                '3rd Places',
                '4th Places',
            ]);

            // Data rows
            foreach ($standings as $standing) {
                fputcsv($file, $standing);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

