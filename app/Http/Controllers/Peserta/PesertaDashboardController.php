<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk Dashboard Peserta
 * 
 * Menampilkan informasi pendaftaran, kompetisi,
 * status pembayaran, dan submission peserta
 */
class PesertaDashboardController extends Controller
{
    /**
     * Tampilkan dashboard peserta
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $user = Auth::user();

            // Statistik utama
            $stats = $this->getParticipantStatistics($user);

            // Registrasi peserta
            $registrations = $this->getUserRegistrations($user);

            // Kompetisi yang tersedia
            $availableCompetitions = $this->getAvailableCompetitions($user);

            // Submission status
            $submissions = $this->getUserSubmissions($user);

            // Upcoming deadlines
            $upcomingDeadlines = $this->getUpcomingDeadlines($user);

            return view('peserta.dashboard', compact(
                'stats',
                'registrations',
                'availableCompetitions',
                'submissions',
                'upcomingDeadlines'
            ));
        } catch (\Exception $e) {
            \Log::error('Error in PesertaDashboardController@index: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return dashboard with empty data
            return view('peserta.dashboard', [
                'stats' => [
                    'total_registrations' => 0,
                    'confirmed_registrations' => 0,
                    'pending_registrations' => 0,
                    'total_paid' => 0,
                    'total_submissions' => 0,
                    'final_submissions' => 0,
                ],
                'registrations' => collect(),
                'availableCompetitions' => collect(),
                'submissions' => collect(),
                'upcomingDeadlines' => []
            ])->with('error', 'Terjadi kesalahan saat memuat dashboard. Silakan refresh halaman.');
        }
    }

    /**
     * Mendapatkan statistik peserta
     * 
     * @param \App\Models\User $user
     * @return array
     */
    protected function getParticipantStatistics($user)
    {
        try {
            $registrations = Registration::where('user_id', $user->id)->get();
            
            return [
                'total_registrations' => $registrations->count(),
                'confirmed_registrations' => $registrations->where('status', 'confirmed')->count(),
                'pending_registrations' => $registrations->whereIn('status', ['pending', 'paid'])->count(),
                'total_paid' => $registrations->whereIn('status', ['confirmed', 'paid'])->sum('amount'),
                'total_submissions' => Submission::whereHas('registration', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count(),
                'final_submissions' => Submission::whereHas('registration', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->where('status', 'submitted')->count(),
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting participant statistics: ' . $e->getMessage());
            
            return [
                'total_registrations' => 0,
                'confirmed_registrations' => 0,
                'pending_registrations' => 0,
                'total_paid' => 0,
                'total_submissions' => 0,
                'final_submissions' => 0,
            ];
        }
    }

    /**
     * Mendapatkan registrasi pengguna
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getUserRegistrations($user)
    {
        return Registration::with(['competition', 'payment', 'submission'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Mendapatkan kompetisi yang tersedia untuk pendaftaran
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getAvailableCompetitions($user)
    {
        // Kompetisi yang masih buka pendaftaran dan belum didaftari user
        $registeredCompetitions = Registration::where('user_id', $user->id)
            ->pluck('competition_id')
            ->toArray();

        return Competition::active()
            ->whereNotIn('id', $registeredCompetitions)
            ->where('registration_start', '<=', now())
            ->where('registration_end', '>=', now())
            ->orderBy('registration_end', 'asc')
            ->take(6)
            ->get();
    }

    /**
     * Mendapatkan submission pengguna
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getUserSubmissions($user)
    {
        return Submission::with(['registration.competition', 'scores'])
            ->whereHas('registration', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    /**
     * Mendapatkan deadline yang akan datang
     * 
     * @param \App\Models\User $user
     * @return array
     */
    protected function getUpcomingDeadlines($user)
    {
        $deadlines = [];
        
        // Registration deadlines
        $registrations = Registration::with('competition')
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'paid'])
            ->get();
            
        foreach ($registrations as $registration) {
            if ($registration->created_at->addHours(24) > now()) {
                $deadlines[] = [
                    'type' => 'payment',
                    'title' => 'Batas Pembayaran - ' . $registration->competition->name,
                    'deadline' => $registration->created_at->addHours(24),
                    'status' => 'warning',
                    'action_url' => route('payment.checkout', $registration),
                ];
            }
        }
        
        // Submission deadlines
        $confirmedRegistrations = Registration::with('competition')
            ->where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->get();
            
        foreach ($confirmedRegistrations as $registration) {
            if ($registration->competition->submission_deadline &&
                $registration->competition->submission_deadline > now()) {

                $submission = Submission::where('registration_id', $registration->id)->first();
                $hasSubmission = !is_null($submission);

                $deadlines[] = [
                    'type' => 'submission',
                    'title' => 'Batas Submit Karya - ' . $registration->competition->name,
                    'deadline' => $registration->competition->submission_deadline,
                    'status' => $hasSubmission ? 'info' : 'danger',
                    'action_url' => $hasSubmission
                        ? route('peserta.submissions.show', $submission)
                        : route('peserta.submissions.create', $registration),
                ];
            }
        }
        
        // Sort by deadline
        usort($deadlines, function($a, $b) {
            return $a['deadline'] <=> $b['deadline'];
        });
        
        return array_slice($deadlines, 0, 5); // Take 5 nearest deadlines
    }
}
