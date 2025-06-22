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
        $jury = Auth::user();
        
        // Get competitions assigned to this jury
        // Assuming there's a jury_competitions pivot table or similar relationship
        $competitions = Competition::where('is_active', true)
            ->whereHas('juries', function ($query) use ($jury) {
                $query->where('user_id', $jury->id);
            })
            ->withCount(['registrations', 'confirmedRegistrations'])
            ->orderBy('competition_start', 'asc')
            ->get();

        // Get scoring progress for each competition
        foreach ($competitions as $competition) {
            $totalParticipants = $competition->confirmed_registrations_count;
            $scoredParticipants = Score::whereHas('registration.competition', function ($query) use ($competition) {
                $query->where('id', $competition->id);
            })
            ->where('jury_id', $jury->id)
            ->distinct('registration_id')
            ->count();

            $competition->scoring_progress = $totalParticipants > 0 
                ? round(($scoredParticipants / $totalParticipants) * 100, 2)
                : 0;
        }

        return view('juri.competitions.index', compact('competitions'));
    }

    /**
     * Tampilkan detail kompetisi
     * 
     * @param \App\Models\Competition $competition
     * @return \Illuminate\View\View
     */
    public function show(Competition $competition)
    {
        $jury = Auth::user();

        // Check if jury is assigned to this competition
        if (!$competition->juries->contains($jury->id)) {
            abort(403, 'Anda tidak memiliki akses ke kompetisi ini.');
        }

        $competition->load(['registrations.user', 'registrations.submissions']);

        // Get confirmed registrations only
        $registrations = $competition->registrations()
            ->where('status', 'confirmed')
            ->with(['user', 'submissions', 'scores' => function ($query) use ($jury) {
                $query->where('jury_id', $jury->id);
            }])
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate scoring statistics
        $totalParticipants = $registrations->count();
        $scoredParticipants = $registrations->filter(function ($registration) {
            return $registration->scores->isNotEmpty();
        })->count();

        $scoringProgress = $totalParticipants > 0 
            ? round(($scoredParticipants / $totalParticipants) * 100, 2)
            : 0;

        return view('juri.competitions.show', compact(
            'competition', 
            'registrations', 
            'totalParticipants', 
            'scoredParticipants', 
            'scoringProgress'
        ));
    }

    /**
     * Tampilkan daftar peserta kompetisi
     * 
     * @param \App\Models\Competition $competition
     * @return \Illuminate\View\View
     */
    public function participants(Competition $competition)
    {
        $jury = Auth::user();

        // Check if jury is assigned to this competition
        if (!$competition->juries->contains($jury->id)) {
            abort(403, 'Anda tidak memiliki akses ke kompetisi ini.');
        }

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
