<?php

namespace App\Http\Controllers\Juri;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Score;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk Dashboard Juri
 * 
 * Menampilkan informasi kompetisi yang harus dinilai,
 * progress penilaian, dan statistik juri
 */
class JuriDashboardController extends Controller
{
    /**
     * Tampilkan dashboard juri
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $jury = Auth::user();
        
        // Statistik utama
        $stats = $this->getJuryStatistics($jury);
        
        // Kompetisi yang perlu dinilai
        $activeCompetitions = $this->getActiveCompetitions();
        
        // Progress penilaian
        $scoringProgress = $this->getScoringProgress($jury);
        
        // Submission yang belum dinilai
        $pendingSubmissions = $this->getPendingSubmissions($jury);
        
        // Recent activities
        $recentActivities = $this->getRecentActivities($jury);
        
        return view('juri.dashboard', compact(
            'stats',
            'activeCompetitions', 
            'scoringProgress',
            'pendingSubmissions',
            'recentActivities'
        ));
    }

    /**
     * Mendapatkan statistik juri
     * 
     * @param \App\Models\User $jury
     * @return array
     */
    protected function getJuryStatistics($jury)
    {
        try {
            // Get competitions assigned to this jury
            $assignedCompetitions = $jury->juryCompetitions()->where('is_active', true)->count();
            
            return [
                'total_competitions' => Competition::active()->count(),
                'assigned_competitions' => $assignedCompetitions,
                'total_scores' => Score::where('jury_id', $jury->id)->count(),
                'completed_scores' => Score::where('jury_id', $jury->id)->where('is_final', true)->count(),
                'pending_scores' => Score::where('jury_id', $jury->id)->where('is_final', false)->count(),
                'average_score' => round(Score::where('jury_id', $jury->id)->where('is_final', true)->avg('total_score') ?? 0, 2),
            ];
        } catch (\Exception $e) {
            // Log error and return safe defaults
            \Log::error('Error getting jury statistics: ' . $e->getMessage());
            
            return [
                'total_competitions' => 0,
                'assigned_competitions' => 0,
                'total_scores' => 0,
                'completed_scores' => 0,
                'pending_scores' => 0,
                'average_score' => 0,
            ];
        }
    }

    /**
     * Mendapatkan kompetisi aktif yang perlu dinilai
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getActiveCompetitions()
    {
        try {
            return Competition::active()
                ->where('competition_start', '<=', now())
                ->where('competition_end', '>=', now())
                ->withCount(['registrations' => function($query) {
                    $query->where('status', 'confirmed');
                }])
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error getting active competitions: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Mendapatkan progress penilaian juri
     * 
     * @param \App\Models\User $jury
     * @return array
     */
    protected function getScoringProgress($jury)
    {
        try {
            // Get only competitions assigned to this jury
            $competitions = $jury->juryCompetitions()->where('is_active', true)->get();
            $progress = [];

            foreach ($competitions as $competition) {
                $totalSubmissions = Submission::whereHas('registration', function($query) use ($competition) {
                    $query->where('competition_id', $competition->id)
                          ->where('status', 'confirmed');
                })
                    ->where('status', 'submitted')
                    ->count();

                $scoredSubmissions = Score::where('competition_id', $competition->id)
                    ->where('jury_id', $jury->id)
                    ->where('is_final', true)
                    ->count();

                $progress[] = [
                    'competition' => $competition,
                    'total' => $totalSubmissions,
                    'scored' => $scoredSubmissions,
                    'percentage' => $totalSubmissions > 0 ? round(($scoredSubmissions / $totalSubmissions) * 100, 1) : 0,
                ];
            }

            return $progress;
        } catch (\Exception $e) {
            \Log::error('Error getting scoring progress: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Mendapatkan submission yang belum dinilai
     * 
     * @param \App\Models\User $jury
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPendingSubmissions($jury)
    {
        try {
            // Get submissions from competitions assigned to this jury
            $assignedCompetitionIds = $jury->juryCompetitions()->where('competitions.is_active', true)->pluck('competitions.id');
            
            return Submission::with(['registration.user', 'registration.competition'])
                ->where('status', 'submitted')
                ->whereHas('registration', function($query) use ($assignedCompetitionIds) {
                    $query->whereIn('competition_id', $assignedCompetitionIds)
                          ->where('status', 'confirmed');
                })
                ->whereDoesntHave('scores', function($query) use ($jury) {
                    $query->where('jury_id', $jury->id)->where('is_final', true);
                })
                ->orderBy('submitted_at', 'asc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error getting pending submissions: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Mendapatkan aktivitas terbaru juri
     * 
     * @param \App\Models\User $jury
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRecentActivities($jury)
    {
        try {
            return Score::with(['registration.user', 'registration.competition'])
                ->where('jury_id', $jury->id)
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error getting recent activities: ' . $e->getMessage());
            return collect();
        }
    }
}
