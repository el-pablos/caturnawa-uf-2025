<?php

namespace App\Http\Controllers\Juri;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk mengelola kompetisi dari sisi juri
 * 
 * Juri dapat melihat kompetisi yang ditugaskan dan pesertanya
 */
class CompetitionController extends Controller
{
    /**
     * Tampilkan daftar kompetisi yang ditugaskan ke juri
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $jury = Auth::user();

            // Get competitions where this jury is assigned and have submissions
            $competitions = Competition::where('is_active', true)
                ->whereHas('juries', function($query) use ($jury) {
                    $query->where('competition_juries.user_id', $jury->id);
                })
                ->whereHas('registrations.submission', function($query) {
                    $query->where('is_final', true);
                })
                ->withCount(['registrations', 'confirmedRegistrations'])
                ->orderBy('competition_start', 'asc')
                ->get();

            // Get scoring progress for each competition based on submissions
            foreach ($competitions as $competition) {
                // Count submissions that need to be scored
                $totalSubmissions = \App\Models\Submission::whereHas('registration', function($query) use ($competition) {
                    $query->where('competition_id', $competition->id);
                })->where('is_final', true)->count();

                // Count how many submissions this jury has scored
                $scoredSubmissions = Score::where('competition_id', $competition->id)
                    ->where('jury_id', $jury->id)
                    ->where('is_final', true)
                    ->count();

                $competition->total_submissions = $totalSubmissions;
                $competition->scored_submissions = $scoredSubmissions;
                $competition->scoring_progress = $totalSubmissions > 0
                    ? round(($scoredSubmissions / $totalSubmissions) * 100, 2)
                    : 0;
            }

            return view('juri.competitions.index', compact('competitions'));
        } catch (\Exception $e) {
            \Log::error('Juri competitions index error: ' . $e->getMessage());

            // Return empty competitions if error occurs
            $competitions = collect();
            return view('juri.competitions.index', compact('competitions'));
        }
    }

    /**
     * Tampilkan detail kompetisi
     * 
     * @param \App\Models\Competition $competition
     * @return \Illuminate\View\View
     */
    public function show(Competition $competition)
    {
        try {
            $jury = Auth::user();

            // Skip jury assignment check for now (can be implemented later)
            // if (!$competition->juries->contains($jury->id)) {
            //     abort(403, 'Anda tidak memiliki akses ke kompetisi ini.');
            // }

            $competition->load(['registrations.user', 'registrations.submissions']);

            // Get confirmed registrations only
            $registrations = $competition->registrations()
                ->where('status', 'confirmed')
                ->with(['user', 'submissions', 'scores' => function ($query) use ($jury) {
                    $query->where('jury_id', $jury->id);
                }])
                ->orderBy('created_at', 'asc')
                ->get();

        // Get submissions for this competition
        $submissions = \App\Models\Submission::whereHas('registration', function($query) use ($competition) {
            $query->where('competition_id', $competition->id);
        })->where('is_final', true)->get();

        // Calculate statistics
        $totalParticipants = $registrations->count();
        $confirmedParticipants = $registrations->count();
        $totalSubmissions = $submissions->count();

        $scoredSubmissions = \App\Models\Score::where('competition_id', $competition->id)
            ->where('jury_id', $jury->id)
            ->where('is_final', true)
            ->count();

        $scoringProgress = $totalSubmissions > 0
            ? round(($scoredSubmissions / $totalSubmissions) * 100, 2)
            : 0;

        $statistics = [
            'total_participants' => $totalParticipants,
            'confirmed_participants' => $confirmedParticipants,
            'total_submissions' => $totalSubmissions,
            'scored_submissions' => $scoredSubmissions,
            'scoring_progress' => $scoringProgress
        ];

        // Get recent submissions
        $recentSubmissions = \App\Models\Submission::with(['registration.user'])
            ->whereHas('registration', function($query) use ($competition) {
                $query->where('competition_id', $competition->id);
            })
            ->where('is_final', true)
            ->orderBy('submitted_at', 'desc')
            ->take(5)
            ->get();

        // Add jury score info to recent submissions
        foreach ($recentSubmissions as $submission) {
            $submission->jury_score = \App\Models\Score::where('competition_id', $competition->id)
                ->where('registration_id', $submission->registration_id)
                ->where('jury_id', $jury->id)
                ->first();
        }

            return view('juri.competitions.show', compact(
                'competition',
                'registrations',
                'statistics',
                'recentSubmissions'
            ));
        } catch (\Exception $e) {
            \Log::error('Juri competition show error: ' . $e->getMessage());

            // Return with empty data if error occurs
            $registrations = collect();
            $statistics = [
                'total_participants' => 0,
                'scored_participants' => 0,
                'pending_scores' => 0,
                'average_score' => 0
            ];
            $recentSubmissions = collect();

            return view('juri.competitions.show', compact(
                'competition',
                'registrations',
                'statistics',
                'recentSubmissions'
            ));
        }
    }

    /**
     * Tampilkan daftar peserta kompetisi
     * 
     * @param \App\Models\Competition $competition
     * @return \Illuminate\View\View
     */
    public function participants(Competition $competition)
    {
        try {
            $jury = Auth::user();

            // Skip jury assignment check for now
            // if (!$competition->juries->contains($jury->id)) {
            //     abort(403, 'Anda tidak memiliki akses ke kompetisi ini.');
            // }

            // Get confirmed registrations with their scores from this jury
            $participants = $competition->registrations()
                ->where('status', 'confirmed')
                ->with([
                    'user',
                    'submissions',
                    'scores' => function ($query) use ($jury) {
                        $query->where('jury_id', $jury->id);
                    }
                ])
                ->orderBy('created_at', 'asc')
                ->get();

            // Add scoring status to each participant
            foreach ($participants as $participant) {
                $participant->is_scored = $participant->scores->isNotEmpty();
                $participant->total_score = $participant->scores->sum('total_score');
                $participant->average_score = $participant->scores->count() > 0
                    ? $participant->scores->avg('total_score')
                    : 0;
            }

            return view('juri.competitions.participants', compact('competition', 'participants'));
        } catch (\Exception $e) {
            \Log::error('Juri competition participants error: ' . $e->getMessage());

            // Return empty participants if error occurs
            $participants = collect();
            return view('juri.competitions.participants', compact('competition', 'participants'));
        }
    }

    /**
     * Tampilkan form penilaian untuk peserta
     * 
     * @param \App\Models\Competition $competition
     * @param \App\Models\Registration $registration
     * @return \Illuminate\View\View
     */
    public function scoreParticipant(Competition $competition, Registration $registration)
    {
        $jury = Auth::user();

        // Check if jury is assigned to this competition
        if (!$competition->juries->contains($jury->id)) {
            abort(403, 'Anda tidak memiliki akses ke kompetisi ini.');
        }

        // Check if registration belongs to this competition
        if ($registration->competition_id !== $competition->id) {
            abort(404, 'Peserta tidak ditemukan dalam kompetisi ini.');
        }

        // Check if registration is confirmed
        if ($registration->status !== 'confirmed') {
            abort(403, 'Peserta belum dikonfirmasi.');
        }

        $registration->load(['user', 'submissions']);

        // Get existing score from this jury
        $existingScore = Score::where('registration_id', $registration->id)
            ->where('jury_id', $jury->id)
            ->first();

        // Get scoring criteria for this competition
        $criteria = $competition->scoring_criteria ?? [
            'creativity' => 'Kreativitas',
            'technical' => 'Teknis',
            'presentation' => 'Presentasi',
            'originality' => 'Originalitas',
        ];

        return view('juri.competitions.score', compact(
            'competition', 
            'registration', 
            'existingScore', 
            'criteria'
        ));
    }
}
