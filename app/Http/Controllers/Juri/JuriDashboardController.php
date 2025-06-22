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
        return [
            'total_competitions' => Competition::active()->count(),
            'assigned_competitions' => Competition::active()->count(), // TODO: Filter by assigned competitions
            'total_scores' => Score::where('jury_id', $jury->id)->count(),
            'completed_scores' => Score::where('jury_id', $jury->id)->where('is_final', true)->count(),
            'pending_scores' => Score::where('jury_id', $jury->id)->where('is_final', false)->count(),
            'average_score' => Score::where('jury_id', $jury->id)->where('is_final', true)->avg('total_score') ?? 0,
        ];
    }

    /**
     * Mendapatkan kompetisi aktif yang perlu dinilai
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getActiveCompetitions()
    {
        return Competition::active()
            ->where('competition_start', '<=', now())
            ->where('competition_end', '>=', now())
            ->withCount(['registrations' => function($query) {
                $query->where('status', 'confirmed');
            }])
            ->get();
    }

    /**
     * Mendapatkan progress penilaian juri
     * 
     * @param \App\Models\User $jury
     * @return array
     */
    protected function getScoringProgress($jury)
    {
        $competitions = Competition::active()->get();
        $progress = [];
        
        foreach ($competitions as $competition) {
            $totalSubmissions = Submission::where('competition_id', $competition->id)
                ->where('is_final', true)
                ->count();
                
            $scoredSubmissions = Score::where('competition_id', $competition->id)
                ->where('jury_id', $jury->id)
                ->where('is_final', true)
                ->count();
                
            $progress[] = [
                'competition' => $competition,
                'total' => $totalSubmissions,
                'scored' => $scoredSubmissions,
                'percentage' => $totalSubmissions > 0 ? ($scoredSubmissions / $totalSubmissions) * 100 : 0,
            ];
        }
        
        return $progress;
    }

    /**
     * Mendapatkan submission yang belum dinilai
     * 
     * @param \App\Models\User $jury
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPendingSubmissions($jury)
    {
        return Submission::with(['registration.user', 'competition'])
            ->where('is_final', true)
            ->whereDoesntHave('scores', function($query) use ($jury) {
                $query->where('jury_id', $jury->id)->where('is_final', true);
            })
            ->orderBy('submitted_at', 'asc')
            ->take(10)
            ->get();
    }

    /**
     * Mendapatkan aktivitas terbaru juri
     * 
     * @param \App\Models\User $jury
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRecentActivities($jury)
    {
        return Score::with(['registration.user', 'competition'])
            ->where('jury_id', $jury->id)
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
    }
}
