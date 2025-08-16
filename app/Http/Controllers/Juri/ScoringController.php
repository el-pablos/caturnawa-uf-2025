<?php

namespace App\Http\Controllers\Juri;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Score;
use App\Models\Submission;
use App\Models\CompetitionRound;
use App\Models\RoundMatch;
use App\Models\TeamMatchup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Controller untuk Sistem Penilaian Juri
 * 
 * Menangani proses penilaian submission peserta
 * oleh juri dengan berbagai kriteria
 */
class ScoringController extends Controller
{
    /**
     * Tampilkan daftar kompetisi untuk penilaian
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $jury = Auth::user();

        $competitions = Competition::active()
            ->where('competition_start', '<=', now())
            ->withCount([
                'registrations' => function($query) {
                    $query->where('status', 'confirmed');
                }
            ])
            ->get();

        // Add scores count manually to avoid relationship issues
        foreach ($competitions as $competition) {
            $competition->scores_count = Score::where('competition_id', $competition->id)
                ->where('jury_id', $jury->id)
                ->where('is_final', true)
                ->count();
        }

        // Add submission counts manually
        foreach ($competitions as $competition) {
            $competition->submissions_count = Submission::whereHas('registration', function($query) use ($competition) {
                $query->where('competition_id', $competition->id);
            })->where('is_final', true)->count();
        }

        // Get all submissions for statistics
        $allSubmissions = Submission::whereHas('registration.competition', function($query) use ($jury) {
            $query->whereHas('juries', function($q) use ($jury) {
                $q->where('user_id', $jury->id);
            });
        })->where('is_final', true)->get();

        // Calculate statistics
        $totalSubmissions = $allSubmissions->count();
        $scoredSubmissions = Score::where('jury_id', $jury->id)->where('is_final', true)->count();
        $pendingSubmissions = $totalSubmissions - $scoredSubmissions;

        // Calculate average score
        $averageScore = Score::where('jury_id', $jury->id)
            ->where('is_final', true)
            ->avg('total_score') ?: 0;

        // Get submissions for display (with pagination)
        $submissions = Submission::with(['registration.user', 'registration.competition'])
            ->whereHas('registration.competition.juries', function($query) use ($jury) {
                $query->where('user_id', $jury->id);
            })
            ->where('is_final', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Add score information to each submission
        foreach ($submissions as $submission) {
            $score = Score::where('registration_id', $submission->registration_id)
                ->where('jury_id', $jury->id)
                ->first();
            $submission->jury_score = $score;
        }

        return view('juri.scoring.index', compact(
            'competitions',
            'submissions',
            'totalSubmissions',
            'scoredSubmissions',
            'pendingSubmissions',
            'averageScore'
        ));
    }

    /**
     * Tampilkan daftar submission dalam kompetisi
     * 
     * @param \App\Models\Competition $competition
     * @return \Illuminate\View\View
     */
    public function competition(Competition $competition)
    {
        $submissions = Submission::with(['registration.user', 'registration.competition'])
            ->whereHas('registration', function ($query) use ($competition) {
                $query->where('competition_id', $competition->id);
            })
            ->where('is_final', true)
            ->get();

        $jury = Auth::user();
        
        // Tambahkan informasi score untuk setiap submission
        foreach ($submissions as $submission) {
            $score = Score::where('competition_id', $competition->id)
                ->where('registration_id', $submission->registration_id)
                ->where('jury_id', $jury->id)
                ->first();
                
            $submission->jury_score = $score;
            $submission->is_scored = $score && $score->is_final;
        }

        return view('juri.scoring.competition', compact('competition', 'submissions'));
    }

    /**
     * Tampilkan form penilaian untuk submission
     * 
     * @param \App\Models\Submission $submission
     * @return \Illuminate\View\View
     */
    public function submission(Submission $submission)
    {
        $jury = Auth::user();
        
        // Cari score yang sudah ada
        $score = Score::where('competition_id', $submission->registration->competition_id)
            ->where('registration_id', $submission->registration_id)
            ->where('jury_id', $jury->id)
            ->first();

        // Jika belum ada score, buat baru
        if (!$score) {
            $score = new Score([
                'competition_id' => $submission->registration->competition_id,
                'registration_id' => $submission->registration_id,
                'jury_id' => $jury->id,
                'criteria_scores' => [],
                'is_final' => false,
            ]);
        }

        // Use specific criteria based on competition type
        $competition = $submission->registration->competition;
        if ($competition && $competition->isEdcCompetition()) {
            $criteria = Score::getEdcCriteria();
        } elseif ($competition && $competition->isKdbiCompetition()) {
            $criteria = Score::getKdbiCriteria();
        } elseif ($competition && $competition->isSpcCompetition()) {
            $criteria = Score::getSpcCriteria();
        } elseif ($competition && $competition->category === 'event_dcc') {
            // For DCC, determine criteria based on competition name and current round
            // Get current judging phase based on timeline
            $currentPhase = $this->getCurrentDccJudgingPhase($competition);
            
            if (str_contains(strtolower($competition->name), 'infographics')) {
                $criteria = Score::getDccInfografisCriteria($currentPhase);
            } elseif (str_contains(strtolower($competition->name), 'short video') || str_contains(strtolower($competition->name), 'video')) {
                $criteria = Score::getDccShortVideoCriteria($currentPhase);
            } else {
                $criteria = Score::getDccShortVideoCriteria('preliminary_round'); // Default to preliminary
            }
        } else {
            $criteria = Score::getDefaultCriteria();
        }
        
        return view('juri.scoring.submission', compact('submission', 'score', 'criteria'));
    }

    /**
     * Simpan atau update penilaian
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Submission $submission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Submission $submission)
    {
        $competition = $submission->registration->competition;
        $isEdcCompetition = $competition && $competition->isEdcCompetition();
        $isKdbiCompetition = $competition && $competition->isKdbiCompetition();
        $isSpcCompetition = $competition && $competition->isSpcCompetition();
        $isDccCompetition = $competition && $competition->category === 'event_dcc';

        // Use specific criteria based on competition type
        if ($isEdcCompetition) {
            $criteria = Score::getEdcCriteria();
        } elseif ($isKdbiCompetition) {
            $criteria = Score::getKdbiCriteria();
        } elseif ($isSpcCompetition) {
            $criteria = Score::getSpcCriteria();
        } elseif ($isDccCompetition) {
            // For DCC, determine criteria based on competition name and current round
            $currentPhase = $this->getCurrentDccJudgingPhase($competition);
            
            if (str_contains(strtolower($competition->name), 'infographics')) {
                $criteria = Score::getDccInfografisCriteria($currentPhase);
            } elseif (str_contains(strtolower($competition->name), 'short video') || str_contains(strtolower($competition->name), 'video')) {
                $criteria = Score::getDccShortVideoCriteria($currentPhase);
            } else {
                $criteria = Score::getDccShortVideoCriteria('preliminary_round'); // Default to preliminary
            }
        } else {
            $criteria = Score::getDefaultCriteria();
        }
        
        $rules = [];
        
        // Dynamic validation rules for each criteria
        foreach (array_keys($criteria) as $criteriaKey) {
            if ($isEdcCompetition || $isKdbiCompetition) {
                // EDC and KDBI use 50-100 range
                $rules["criteria.{$criteriaKey}"] = 'required|numeric|min:50|max:100';
            } elseif ($isSpcCompetition || $isDccCompetition) {
                // SPC and DCC use 0-100 range
                $rules["criteria.{$criteriaKey}"] = 'required|numeric|min:0|max:100';
            } else {
                // Other competitions use 0-100 range
                $rules["criteria.{$criteriaKey}"] = 'required|numeric|min:0|max:100';
            }
        }
        
        $rules['comments'] = 'nullable|string|max:1000';

        $messages = [
            'criteria.*.required' => 'Semua kriteria penilaian harus diisi',
            'criteria.*.numeric' => 'Nilai harus berupa angka',
        ];
        
        if ($isEdcCompetition) {
            $messages['criteria.*.min'] = 'Nilai minimal 50 (sesuai standar EDC)';
            $messages['criteria.*.max'] = 'Nilai maksimal 100';
        } elseif ($isKdbiCompetition) {
            $messages['criteria.*.min'] = 'Nilai minimal 50 (sesuai standar KDBI)';
            $messages['criteria.*.max'] = 'Nilai maksimal 100';
        } elseif ($isSpcCompetition) {
            $messages['criteria.*.min'] = 'Nilai minimal 0 (sesuai standar SPC)';
            $messages['criteria.*.max'] = 'Nilai maksimal 100';
        } elseif ($isDccCompetition) {
            $messages['criteria.*.min'] = 'Nilai minimal 0 (sesuai standar DCC)';
            $messages['criteria.*.max'] = 'Nilai maksimal 100';
        } else {
            $messages['criteria.*.min'] = 'Nilai minimal 0';
            $messages['criteria.*.max'] = 'Nilai maksimal 100';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $jury = Auth::user();
        
        // Find or create score
        $score = Score::firstOrNew([
            'competition_id' => $submission->registration->competition_id,
            'registration_id' => $submission->registration_id,
            'jury_id' => $jury->id,
        ]);

        $score->criteria_scores = $request->criteria;
        $score->comments = $request->comments;
        $score->is_final = $request->has('is_final') && $request->is_final;

        // Calculate total score
        $totalScore = 0;
        if ($request->criteria) {
            foreach ($request->criteria as $criteriaKey => $value) {
                $totalScore += (float) $value;
            }
        }
        $score->total_score = $totalScore;

        if ($score->is_final) {
            $score->submitted_at = now();
        }

        $score->save();

        $message = $score->is_final ?
            'Penilaian berhasil disubmit sebagai final.' :
            'Penilaian berhasil disimpan sebagai draft.';

        return redirect()->route('juri.scoring.submission', $submission)
            ->with('success', $message);
    }

    /**
     * Update penilaian yang sudah ada
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Score $score
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Score $score)
    {
        // Pastikan juri hanya bisa update score miliknya sendiri
        if ($score->jury_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah penilaian ini.');
        }

        $criteria = Score::getDefaultCriteria();
        $rules = [];
        
        foreach (array_keys($criteria) as $criteriaKey) {
            $rules["criteria.{$criteriaKey}"] = 'required|numeric|min:0|max:100';
        }
        
        $rules['comments'] = 'nullable|string|max:1000';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $score->update([
            'criteria_scores' => $request->criteria,
            'comments' => $request->comments,
        ]);

        return back()->with('success', 'Penilaian berhasil diperbarui.');
    }

    /**
     * Submit penilaian sebagai final
     * 
     * @param \App\Models\Score $score
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Score $score)
    {
        // Pastikan juri hanya bisa submit score miliknya sendiri
        if ($score->jury_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah penilaian ini.');
        }

        // Validasi apakah semua kriteria sudah diisi
        if (!$score->isComplete()) {
            return back()->with('error', 'Semua kriteria penilaian harus diisi sebelum submit final.');
        }

        $score->submitFinal();

        return redirect()->route('juri.scoring.competition', $score->competition)
            ->with('success', 'Penilaian berhasil disubmit sebagai final.');
    }

    /**
     * Tampilkan daftar babak kompetisi untuk penilaian
     */
    public function rounds()
    {
        $jury = Auth::user();

        // Get competitions with rounds where this jury is assigned
        $competitions = Competition::active()
            ->whereHas('rounds.matches.teamMatchups', function($query) use ($jury) {
                $query->where('jury_id', $jury->id);
            })
            ->with(['rounds' => function($query) use ($jury) {
                $query->whereHas('matches.teamMatchups', function($subQuery) use ($jury) {
                    $subQuery->where('jury_id', $jury->id);
                });
            }])
            ->get();

        return view('juri.scoring.rounds', compact('competitions'));
    }

    /**
     * Tampilkan form penilaian untuk pertandingan tertentu
     */
    public function scoreMatch(RoundMatch $match)
    {
        $jury = Auth::user();

        // Check if jury is assigned to this match
        $assignedMatchups = $match->teamMatchups()->where('jury_id', $jury->id)->get();
        if ($assignedMatchups->isEmpty()) {
            abort(403, 'Anda tidak memiliki akses untuk menilai pertandingan ini.');
        }

        $competition = $match->competitionRound->competition;
        $scoringCriteria = $competition->scoringCriteria()->active()->ordered()->get();

        return view('juri.scoring.match', compact('match', 'assignedMatchups', 'scoringCriteria', 'competition'));
    }

    /**
     * Simpan penilaian untuk pertandingan
     */
    public function storeMatchScore(Request $request, RoundMatch $match)
    {
        $jury = Auth::user();

        // Validate that jury is assigned to this match
        $assignedMatchups = $match->teamMatchups()->where('jury_id', $jury->id)->pluck('id')->toArray();
        if (empty($assignedMatchups)) {
            abort(403, 'Anda tidak memiliki akses untuk menilai pertandingan ini.');
        }

        $competition = $match->competitionRound->competition;
        $scoringCriteria = $competition->scoringCriteria()->active()->get();

        // Validate scores for each team matchup
        $rules = [];
        foreach ($assignedMatchups as $matchupId) {
            foreach ($scoringCriteria as $criteria) {
                $rules["scores.{$matchupId}.{$criteria->id}"] = "required|numeric|min:0|max:{$criteria->max_score}";
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Save scores for each team matchup
        foreach ($request->scores as $matchupId => $scores) {
            if (!in_array($matchupId, $assignedMatchups)) {
                continue;
            }

            $matchup = TeamMatchup::find($matchupId);
            if (!$matchup) {
                continue;
            }

            // Calculate individual scores and team score
            $individualScores = [];
            $totalScore = 0;
            $totalWeight = 0;

            foreach ($scores as $criteriaId => $score) {
                $criteria = $scoringCriteria->find($criteriaId);
                if ($criteria) {
                    $individualScores[$criteria->criteria_name] = $score;
                    $totalScore += $score * $criteria->weight;
                    $totalWeight += $criteria->weight;
                }
            }

            $teamScore = $totalWeight > 0 ? $totalScore / $totalWeight : 0;

            // Update team matchup with scores
            $matchup->update([
                'individual_scores' => $individualScores,
                'team_score' => round($teamScore, 2),
            ]);
        }

        // Calculate victory points and rankings for this match
        $this->calculateVictoryPoints($match);

        return redirect()->route('juri.scoring.rounds')
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    /**
     * Tampilkan detail peserta untuk penilaian
     *
     * @param \App\Models\Registration $registration
     * @return \Illuminate\View\View
     */
    public function participant(Registration $registration)
    {
        $jury = Auth::user();

        // Check if jury has access to this registration's competition
        $competition = $registration->competition;
        if (!$competition->juries->contains($jury->id)) {
            abort(403, 'Anda tidak memiliki akses untuk menilai peserta ini.');
        }

        // Get existing score from this jury
        $existingScore = Score::where('registration_id', $registration->id)
            ->where('jury_id', $jury->id)
            ->first();

        $criteria = Score::getDefaultCriteria();

        return view('juri.scoring.participant', compact('registration', 'existingScore', 'criteria'));
    }

    /**
     * Simpan penilaian untuk peserta
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function score(Request $request, Registration $registration)
    {
        $jury = Auth::user();

        // Check if jury has access to this registration's competition
        $competition = $registration->competition;
        if (!$competition->juries->contains($jury->id)) {
            abort(403, 'Anda tidak memiliki akses untuk menilai peserta ini.');
        }

        $criteria = Score::getDefaultCriteria();
        $rules = [];

        // Dynamic validation rules for each criteria
        foreach (array_keys($criteria) as $criteriaKey) {
            $rules["criteria.{$criteriaKey}"] = 'required|numeric|min:0|max:100';
        }

        $rules['comments'] = 'nullable|string|max:1000';

        $validator = Validator::make($request->all(), $rules, [
            'criteria.*.required' => 'Semua kriteria penilaian harus diisi',
            'criteria.*.numeric' => 'Nilai harus berupa angka',
            'criteria.*.min' => 'Nilai minimal 0',
            'criteria.*.max' => 'Nilai maksimal 100',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Find or create score
        $score = Score::firstOrNew([
            'competition_id' => $registration->competition_id,
            'registration_id' => $registration->id,
            'jury_id' => $jury->id,
        ]);

        $score->criteria_scores = $request->criteria;
        $score->comments = $request->comments;
        $score->is_final = $request->has('is_final') && $request->is_final;

        // Calculate total score
        $totalScore = 0;
        if ($request->criteria) {
            foreach ($request->criteria as $criteriaKey => $value) {
                $totalScore += (float) $value;
            }
        }
        $score->total_score = $totalScore;

        if ($score->is_final) {
            $score->submitted_at = now();
        }

        $score->save();

        $message = $score->is_final ?
            'Penilaian berhasil disubmit sebagai final.' :
            'Penilaian berhasil disimpan sebagai draft.';

        return redirect()->route('juri.scoring.participant', $registration)
            ->with('success', $message);
    }

    /**
     * Finalisasi penilaian untuk kompetisi
     *
     * @param \App\Models\Competition $competition
     * @return \Illuminate\Http\RedirectResponse
     */
    public function finalize(Competition $competition)
    {
        $jury = Auth::user();

        // Check if jury has access to this competition
        if (!$competition->juries->contains($jury->id)) {
            abort(403, 'Anda tidak memiliki akses untuk menfinalisasi kompetisi ini.');
        }

        // Get all scores from this jury for this competition
        $scores = Score::where('competition_id', $competition->id)
            ->where('jury_id', $jury->id)
            ->get();

        // Mark all scores as final
        foreach ($scores as $score) {
            if (!$score->is_final && $score->isComplete()) {
                $score->submitFinal();
            }
        }

        return redirect()->route('juri.scoring.competition', $competition)
            ->with('success', 'Semua penilaian untuk kompetisi ini telah difinalisasi.');
    }

    /**
     * Determine current DCC judging phase based on timeline
     *
     * @param \App\Models\Competition $competition
     * @return string
     */
    private function getCurrentDccJudgingPhase(Competition $competition)
    {
        $now = now();
        $timeline = Competition::getAdjustedDccTimeline();
        
        // Check current date against DCC timeline
        if ($now->lt(\Carbon\Carbon::parse($timeline['preliminary_judging_start']))) {
            return 'preliminary_round';
        } elseif ($now->lt(\Carbon\Carbon::parse($timeline['semifinal_judging_start']))) {
            return 'preliminary_round';
        } elseif ($now->lt(\Carbon\Carbon::parse($timeline['final_judging_start']))) {
            return 'semifinal_round';
        } else {
            return 'final_round';
        }
    }

    /**
     * Calculate victory points and rankings for a match
     */
    private function calculateVictoryPoints(RoundMatch $match)
    {
        $matchups = $match->teamMatchups()
            ->whereNotNull('team_score')
            ->orderBy('team_score', 'desc')
            ->get();

        $victoryPointsMap = [3, 2, 1, 0]; // For 4 teams

        foreach ($matchups as $index => $matchup) {
            $matchup->update([
                'ranking' => $index + 1,
                'victory_points' => $victoryPointsMap[$index] ?? 0,
            ]);
        }
    }
}
