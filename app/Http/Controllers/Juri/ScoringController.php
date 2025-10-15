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
            ->whereHas('juries', function($query) use ($jury) {
                $query->where('competition_juries.user_id', $jury->id);
            })
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
                $q->where('competition_juries.user_id', $jury->id);
            });
        })->where('is_final', true)->get();

        // Calculate statistics - fix logic
        $totalSubmissions = $allSubmissions->count();
        $scoredSubmissions = Score::where('jury_id', $jury->id)->where('is_final', true)->count();
        
        // Get actual pending submissions by checking which submissions don't have final scores from this jury
        $pendingSubmissions = 0;
        foreach ($allSubmissions as $submission) {
            $hasScore = Score::where('registration_id', $submission->registration_id)
                ->where('jury_id', $jury->id)
                ->where('is_final', true)
                ->exists();
            if (!$hasScore) {
                $pendingSubmissions++;
            }
        }

        // Calculate average score
        $averageScore = Score::where('jury_id', $jury->id)
            ->where('is_final', true)
            ->avg('total_score') ?: 0;

        // Get submissions for display (with pagination)
        $submissions = Submission::with(['registration.user', 'registration.competition'])
            ->whereHas('registration.competition.juries', function($query) use ($jury) {
                $query->where('competition_juries.user_id', $jury->id);
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
        
        // Load necessary relationships
        $submission->load(['registration.user', 'registration.competition']);
        
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
        $rawCriteria = [];
        
        if ($competition && $competition->isEdcCompetition()) {
            $rawCriteria = Score::getEdcCriteria();
        } elseif ($competition && $competition->isKdbiCompetition()) {
            $rawCriteria = Score::getKdbiCriteria();
        } elseif ($competition && $competition->isSpcCompetition()) {
            $rawCriteria = Score::getSpcCriteria();
        } elseif ($competition && $competition->category === 'event_dcc') {
            // For DCC, determine criteria based on competition name and current round
            // Get current judging phase based on timeline
            $currentPhase = $this->getCurrentDccJudgingPhase($competition);
            
            if (str_contains(strtolower($competition->name), 'infographics')) {
                $rawCriteria = Score::getDccInfografisCriteria($currentPhase);
            } elseif (str_contains(strtolower($competition->name), 'short video') || str_contains(strtolower($competition->name), 'video')) {
                $rawCriteria = Score::getDccShortVideoCriteria($currentPhase);
            } else {
                $rawCriteria = Score::getDccShortVideoCriteria('preliminary_round'); // Default to preliminary
            }
        } else {
            $rawCriteria = Score::getDefaultCriteria();
        }
        
        // Transform complex criteria format to simple format expected by view
        $criteria = [];
        foreach ($rawCriteria as $key => $value) {
            if (is_array($value) && isset($value['max_score'])) {
                // Complex format: extract max_score
                $criteria[$key] = $value['max_score'];
            } else {
                // Simple format: use as is
                $criteria[$key] = $value;
            }
        }
        
        // Get competition-specific data for view
        $competitionType = $competition->category;
        $competitionName = strtolower($competition->name);
        $minScore = 0;
        $maxScore = 100;
        
        // Set score range berdasarkan kompetisi
        if ($competitionType == 'event_debate' || 
            str_contains($competitionName, 'edc') || 
            str_contains($competitionName, 'kdbi')) {
            $minScore = 50; // EDC dan KDBI range 50-100
        }
        
        // Get competition-specific criteria descriptions
        $criteriaDescriptions = $this->getCriteriaDescriptions($competitionName, $competitionType, $rawCriteria);
        
        // Debug data types to prevent array errors
        \Log::info('Debug scoring data', [
            'submission_id' => $submission->id,
            'competition_name' => $competitionName,
            'competition_type' => $competitionType,
            'criteria_descriptions_count' => count($criteriaDescriptions),
            'criteria_descriptions_sample' => array_slice($criteriaDescriptions, 0, 2),
            'score_exists' => $score ? 'yes' : 'no',
            'score_criteria_scores_type' => $score && $score->criteria_scores ? gettype($score->criteria_scores) : 'null',
            'submission_technologies_type' => gettype($submission->technologies),
            'all_variables_types' => [
                'submission' => gettype($submission),
                'score' => gettype($score),
                'criteria' => gettype($criteria),
                'competitionType' => gettype($competitionType),
                'competitionName' => gettype($competitionName),
                'minScore' => gettype($minScore),
                'maxScore' => gettype($maxScore),
                'criteriaDescriptions' => gettype($criteriaDescriptions)
            ]
        ]);

        return view('juri.scoring.show', compact(
            'submission',
            'score',
            'criteria',
            'competitionType',
            'competitionName',
            'minScore',
            'maxScore',
            'criteriaDescriptions'
        ));
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
        $rawCriteria = [];
        if ($isEdcCompetition) {
            $rawCriteria = Score::getEdcCriteria();
        } elseif ($isKdbiCompetition) {
            $rawCriteria = Score::getKdbiCriteria();
        } elseif ($isSpcCompetition) {
            $rawCriteria = Score::getSpcCriteria();
        } elseif ($isDccCompetition) {
            // For DCC, determine criteria based on competition name and current round
            $currentPhase = $this->getCurrentDccJudgingPhase($competition);
            
            if (str_contains(strtolower($competition->name), 'infographics')) {
                $rawCriteria = Score::getDccInfografisCriteria($currentPhase);
            } elseif (str_contains(strtolower($competition->name), 'short video') || str_contains(strtolower($competition->name), 'video')) {
                $rawCriteria = Score::getDccShortVideoCriteria($currentPhase);
            } else {
                $rawCriteria = Score::getDccShortVideoCriteria('preliminary_round'); // Default to preliminary
            }
        } else {
            $rawCriteria = Score::getDefaultCriteria();
        }
        
        // Transform complex criteria format to simple format for validation
        $criteria = [];
        foreach ($rawCriteria as $key => $value) {
            if (is_array($value) && isset($value['max_score'])) {
                // Complex format: extract max_score
                $criteria[$key] = $value['max_score'];
            } else {
                // Simple format: use as is
                $criteria[$key] = $value;
            }
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

        // Build validation rules
        $rules = [
            'total_score' => 'nullable|numeric|min:0|max:100',
            'comments' => 'nullable|string|max:1000',
            'feedback' => 'nullable|string|max:1000',
            'is_final' => 'nullable|boolean',
        ];

        // Add criteria validation if provided
        if ($request->has('criteria')) {
            $criteria = Score::getDefaultCriteria();
            foreach (array_keys($criteria) as $criteriaKey) {
                $rules["criteria.{$criteriaKey}"] = 'required|numeric|min:0|max:100';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Prepare update data
        $updateData = [];

        if ($request->has('criteria')) {
            $updateData['criteria_scores'] = $request->criteria;
        }

        if ($request->has('total_score')) {
            $updateData['total_score'] = $request->total_score;
        }

        if ($request->has('comments')) {
            $updateData['comments'] = $request->comments;
        }

        if ($request->has('feedback')) {
            $updateData['feedback'] = $request->feedback;
        }

        if ($request->has('is_final')) {
            $updateData['is_final'] = $request->is_final;
        }

        $score->update($updateData);

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
     * Get current DCC judging phase berdasarkan timeline kompetisi
     * 
     * @param \App\Models\Competition $competition
     * @return string
     */
    private function getCurrentDccJudgingPhase(Competition $competition)
    {
        $now = now();
        
        // First try to get from Competition timeline if available
        try {
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
        } catch (\Exception $e) {
            // Fallback to competition-specific judging dates
            if ($competition->judging_start && $competition->judging_end) {
                $judgingDuration = $competition->judging_start->diffInDays($competition->judging_end);
                $daysSinceStart = $competition->judging_start->diffInDays($now);
                
                // Jika durasi judging kurang dari 7 hari, anggap hanya preliminary
                if ($judgingDuration <= 7) {
                    return 'preliminary_round';
                }
                
                // Jika durasi 8-21 hari, bagi menjadi 2 fase
                if ($judgingDuration <= 21) {
                    $halfDuration = $judgingDuration / 2;
                    return $daysSinceStart <= $halfDuration ? 'preliminary_round' : 'semifinal_round';
                }
                
                // Jika durasi lebih dari 21 hari, bagi menjadi 3 fase
                $thirdDuration = $judgingDuration / 3;
                if ($daysSinceStart <= $thirdDuration) {
                    return 'preliminary_round';
                } elseif ($daysSinceStart <= ($thirdDuration * 2)) {
                    return 'semifinal_round';
                } else {
                    return 'final_round';
                }
            }
        }
        
        // Default ke preliminary jika tidak ada info judging timeline
        return 'preliminary_round';
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
     * Get criteria descriptions based on competition
     */
    private function getCriteriaDescriptions($competitionName, $competitionType, $rawCriteria = [])
    {
        // First try to extract descriptions from the raw criteria if available
        $descriptions = [];
        foreach ($rawCriteria as $key => $value) {
            if (is_array($value) && isset($value['description'])) {
                $descriptions[$key] = $value['description'];
            }
        }
        
        // If we got descriptions from raw criteria, use them
        if (!empty($descriptions)) {
            return $descriptions;
        }
        
        // Fallback to hardcoded descriptions
        if (str_contains($competitionName, 'edc')) {
            return [
                'theme_alignment' => 'Kesesuaian argumen dengan fokus tema yang diangkat',
                'evidence_quality' => 'Kualitas dan relevansi data/bukti yang mendukung argumen',
                'novelty_interest' => 'Tingkat kebaruan dan inovasi dalam penyampaian argumen',
                'argumentation' => 'Kekuatan struktur argumentasi dan logika berpikir',
                'delivery_style' => 'Gaya penyampaian, kepercayaan diri, dan kredibilitas',
                'poi_response' => 'Kemampuan merespon Point of Information dengan baik'
            ];
        } elseif (str_contains($competitionName, 'kdbi')) {
            return [
                'kesesuaian_tema' => 'Sejauh mana argumen sejalan dengan fokus tema yang diangkat',
                'evidence_based' => 'Kualitas dan relevansi data/bukti yang digunakan untuk mendukung argumen',
                'ketertarikan_novelty' => 'Tingkat kebaruan dan inovasi dalam penyampaian argumen',
                'delivery_style' => 'Cara penyampaian argumentasi meliputi kepercayaan diri, kredibilitas, dan penguasaan emosi'
            ];
        } elseif (str_contains($competitionName, 'infografis')) {
            return [
                'kerapihan_struktur' => 'Karya yang dibuat terstruktur dan mudah dipahami',
                'judul_kreatif_dan_menarik' => 'Judul singkat, jelas, relevan dengan tema dan menggunakan tipografi menarik',
                'isi_pesan' => 'Singkat, padat, dan bahasa yang digunakan jelas keterbacaannya',
                'desain_visual' => 'Penyusunan elemen yang proporsional dan warna yang menarik',
                'teori_dan_konsep_jelas' => 'Keberhasilan menyampaikan pesan dengan ide atau tema yang kuat',
                'komposisi_gambar' => 'Penataan harmonis dan pengaturan elemen-elemen visual',
                'kualitas_editing' => 'Tingkat ketelitian dalam proses pembuatan poster'
            ];
        } elseif (str_contains($competitionName, 'video') || str_contains($competitionName, 'short')) {
            return [
                'durasi_video' => 'Sesuai dengan durasi yang ditentukan yaitu 3 menit',
                'opening_main_title' => 'Memiliki judul utama yang menarik, kreatif, dan relevan',
                'konten_isi_sesuai_tema' => 'Seberapa relevan pesan yang disampaikan dalam video',
                'keefektifan_kalimat' => 'Kalimat harus jelas, singkat, dan mudah dipahami audiens',
                'kualitas_gambar_video' => 'Kualitas video seperti resolusi, kejernihan, dan pencahayaan',
                'kejelasan_caption_text' => 'Caption atau text yang jelas dan tidak mengganggu visual',
                'closing_penutup' => 'Seberapa berkesan penutup video dengan kesimpulan yang kuat'
            ];
        } elseif ($competitionType == 'event_scientific_paper') {
            return [
                'originality_innovation' => 'Tingkat kebaruan, kontribusi, dan inovasi dalam penelitian',
                'methodology_rigor' => 'Kualitas metode penelitian, analisis data, dan ketelitian ilmiah',
                'analysis_discussion' => 'Kedalaman analisis, interpretasi hasil, dan diskusi temuan',
                'writing_structure' => 'Kualitas penulisan akademik, struktur, dan presentasi'
            ];
        }
        
        return [];
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

    /**
     * Show scoring form for a submission
     *
     * @param Submission $submission
     * @return \Illuminate\View\View
     */
    public function scoreForm(Submission $submission)
    {
        $jury = Auth::user();
        $competition = $submission->registration->competition;

        // Verify jury is assigned to this competition
        $isAssigned = $competition->juries()
            ->where('user_id', $jury->id)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'Anda tidak memiliki akses untuk menilai submission ini.');
        }

        // Get existing score if any
        $existingScore = Score::where('registration_id', $submission->registration_id)
            ->where('jury_id', $jury->id)
            ->where('competition_id', $competition->id)
            ->first();

        // Get criteria based on competition type
        $criteria = $this->getCriteriaForCompetition($competition);

        return view('juri.scoring.form', compact('submission', 'competition', 'existingScore', 'criteria'));
    }

    /**
     * Store score for a submission
     *
     * @param Request $request
     * @param Submission $submission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeScore(Request $request, Submission $submission)
    {
        $jury = Auth::user();
        $competition = $submission->registration->competition;

        // Verify jury is assigned to this competition
        $isAssigned = $competition->juries()
            ->where('user_id', $jury->id)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'Anda tidak memiliki akses untuk menilai submission ini.');
        }

        // Validate request
        $validated = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'total_score' => 'required|numeric|min:0|max:100',
            'criteria_scores' => 'nullable|array',
            'comments' => 'nullable|string',
            'feedback' => 'nullable|string',
            'is_final' => 'nullable|boolean',
        ]);

        // Create or update score
        $score = Score::updateOrCreate(
            [
                'registration_id' => $validated['registration_id'],
                'jury_id' => $jury->id,
                'competition_id' => $competition->id,
            ],
            [
                'total_score' => $validated['total_score'],
                'criteria_scores' => $validated['criteria_scores'] ?? [],
                'comments' => $validated['comments'] ?? null,
                'feedback' => $validated['feedback'] ?? null,
                'is_final' => $validated['is_final'] ?? false,
            ]
        );

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan.');
    }

    /**
     * Show jury's own scores
     *
     * @return \Illuminate\View\View
     */
    public function myScores()
    {
        $jury = Auth::user();

        $scores = Score::where('jury_id', $jury->id)
            ->with(['registration.competition', 'registration.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('juri.scoring.my-scores', compact('scores'));
    }

    /**
     * Get criteria for competition
     *
     * @param Competition $competition
     * @return array
     */
    private function getCriteriaForCompetition(Competition $competition)
    {
        if ($competition->isEdcCompetition()) {
            return Score::getEdcCriteria();
        } elseif ($competition->isKdbiCompetition()) {
            return Score::getKdbiCriteria();
        } elseif ($competition->isSpcCompetition()) {
            return Score::getSpcCriteria();
        } elseif ($competition->category === 'event_dcc') {
            return Score::getDccShortVideoCriteria('preliminary_round');
        }

        return Score::getDefaultCriteria();
    }
}
