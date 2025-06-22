<?php

namespace App\Http\Controllers\Juri;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Score;
use App\Models\Submission;
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
        $competitions = Competition::active()
            ->where('competition_start', '<=', now())
            ->withCount([
                'registrations' => function($query) {
                    $query->where('status', 'confirmed');
                },
                'scores' => function($query) {
                    $query->where('jury_id', Auth::id())->where('is_final', true);
                }
            ])
            ->get();

        // Add submission counts manually
        foreach ($competitions as $competition) {
            $competition->submissions_count = Submission::whereHas('registration', function($query) use ($competition) {
                $query->where('competition_id', $competition->id);
            })->where('is_final', true)->count();
        }

        return view('juri.scoring.index', compact('competitions'));
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

        $criteria = Score::getDefaultCriteria();
        
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

        $jury = Auth::user();
        
        // Find or create score
        $score = Score::firstOrNew([
            'competition_id' => $submission->registration->competition_id,
            'registration_id' => $submission->registration_id,
            'jury_id' => $jury->id,
        ]);

        $score->criteria_scores = $request->criteria;
        $score->comments = $request->comments;
        $score->is_final = false; // Save as draft first
        $score->save();

        return redirect()->route('juri.scoring.submission', $submission)
            ->with('success', 'Penilaian berhasil disimpan sebagai draft.');
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
}
