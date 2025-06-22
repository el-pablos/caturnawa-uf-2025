<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Controller untuk Dashboard Admin dan Super Admin
 * 
 * Menampilkan statistik, grafik, dan data overview
 * untuk monitoring sistem secara keseluruhan
 */
class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard utama dengan optimisasi performa
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Hanya load statistik dasar untuk initial load
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return $this->getMainStatistics();
        });

        // Data lainnya akan di-load via AJAX untuk mengurangi initial load time
        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Get chart data via AJAX untuk lazy loading
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartDataAjax()
    {
        $chartData = Cache::remember('admin_dashboard_charts', 600, function () {
            return $this->getChartData();
        });

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Get recent data via AJAX untuk lazy loading
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentDataAjax()
    {
        $recentData = Cache::remember('admin_dashboard_recent', 180, function () {
            return $this->getRecentData();
        });

        return response()->json([
            'success' => true,
            'data' => $recentData
        ]);
    }

    /**
     * Get user distribution via AJAX untuk lazy loading
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDistributionAjax()
    {
        $userDistribution = Cache::remember('admin_dashboard_users', 900, function () {
            return $this->getUserDistribution();
        });

        return response()->json([
            'success' => true,
            'data' => $userDistribution
        ]);
    }

    /**
     * Mendapatkan statistik utama untuk dashboard dengan optimisasi query
     *
     * @return array
     */
    protected function getMainStatistics()
    {
        // Optimisasi dengan single query untuk multiple counts
        $userStats = DB::table('users')
            ->selectRaw('COUNT(*) as total_users')
            ->first();

        $competitionStats = DB::table('competitions')
            ->selectRaw('
                COUNT(*) as total_competitions,
                COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_competitions
            ')
            ->first();

        $registrationStats = DB::table('registrations')
            ->selectRaw('
                COUNT(*) as total_registrations,
                COUNT(CASE WHEN status = "confirmed" THEN 1 END) as confirmed_registrations
            ')
            ->first();

        $paymentStats = DB::table('payments')
            ->selectRaw('
                SUM(CASE WHEN transaction_status = "settlement" THEN gross_amount ELSE 0 END) as total_revenue,
                COUNT(CASE WHEN transaction_status = "pending" THEN 1 END) as pending_payments
            ')
            ->first();

        $submissionCount = DB::table('submissions')->count();

        return [
            'total_users' => $userStats->total_users ?? 0,
            'total_competitions' => $competitionStats->total_competitions ?? 0,
            'active_competitions' => $competitionStats->active_competitions ?? 0,
            'total_registrations' => $registrationStats->total_registrations ?? 0,
            'confirmed_registrations' => $registrationStats->confirmed_registrations ?? 0,
            'total_revenue' => $paymentStats->total_revenue ?? 0,
            'pending_payments' => $paymentStats->pending_payments ?? 0,
            'total_submissions' => $submissionCount ?? 0,
        ];
    }

    /**
     * Mendapatkan data untuk grafik trend pendaftaran
     * 
     * @return array
     */
    protected function getChartData()
    {
        // Trend pendaftaran 6 bulan terakhir
        $registrationTrend = Registration::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Trend pendapatan 6 bulan terakhir  
        $revenueTrend = Payment::select(
            DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as month'),
            DB::raw('SUM(gross_amount) as total')
        )
        ->where('transaction_status', 'settlement')
        ->where('paid_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Format data untuk Chart.js
        $months = [];
        $registrations = [];
        $revenues = [];

        // Generate 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $monthLabel = Carbon::now()->subMonths($i)->format('M Y');
            
            $months[] = $monthLabel;
            
            // Cari data registrasi untuk bulan ini
            $regData = $registrationTrend->firstWhere('month', $month);
            $registrations[] = $regData ? $regData->total : 0;
            
            // Cari data revenue untuk bulan ini
            $revData = $revenueTrend->firstWhere('month', $month);
            $revenues[] = $revData ? floatval($revData->total) : 0;
        }

        return [
            'months' => $months,
            'registrations' => $registrations,
            'revenues' => $revenues,
        ];
    }

    /**
     * Mendapatkan data terbaru untuk dashboard dengan optimisasi
     *
     * @return array
     */
    protected function getRecentData()
    {
        return [
            'recent_users' => User::select('id', 'name', 'email', 'created_at')
                ->with(['roles:id,name'])
                ->latest()
                ->limit(5)
                ->get(),

            'recent_competitions' => Competition::select('id', 'name', 'category', 'created_at', 'is_active')
                ->latest()
                ->limit(3)
                ->get(),

            'recent_payments' => Payment::select('id', 'order_id', 'gross_amount', 'transaction_status', 'created_at', 'registration_id')
                ->with([
                    'registration:id,user_id,competition_id',
                    'registration.user:id,name,email',
                    'registration.competition:id,name'
                ])
                ->latest()
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Mendapatkan distribusi pengguna berdasarkan role
     * 
     * @return array
     */
    protected function getUserDistribution()
    {
        $distribution = User::select('roles.name as role', DB::raw('COUNT(*) as count'))
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->groupBy('roles.name')
            ->get();

        $labels = [];
        $data = [];
        $colors = [
            'Super Admin' => '#dc3545',
            'Admin' => '#fd7e14', 
            'Juri' => '#198754',
            'Peserta' => '#0d6efd',
        ];

        foreach ($distribution as $item) {
            $labels[] = $item->role;
            $data[] = $item->count;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_values($colors),
        ];
    }

    /**
     * Export data statistik dalam format JSON untuk API
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatsApi()
    {
        $stats = $this->getMainStatistics();
        $chartData = $this->getChartData();
        
        return response()->json([
            'success' => true,
            'data' => [
                'statistics' => $stats,
                'chart_data' => $chartData,
                'generated_at' => now()->toISOString(),
            ]
        ]);
    }

    /**
     * Mendapatkan statistik kompetisi
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompetitionStats()
    {
        $competitions = Competition::withCount(['registrations', 'submissions'])
            ->with(['registrations' => function($query) {
                $query->select('competition_id', DB::raw('SUM(amount) as total_revenue'))
                    ->groupBy('competition_id');
            }])
            ->get();

        $stats = $competitions->map(function($competition) {
            return [
                'name' => $competition->name,
                'category' => $competition->category,
                'participants' => $competition->registrations_count,
                'submissions' => $competition->submissions_count,
                'revenue' => $competition->registrations->sum('total_revenue') ?? 0,
                'status' => $competition->is_active ? 'Aktif' : 'Tidak Aktif',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Generate laporan harian
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDailyReport(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $targetDate = Carbon::parse($date);

        $report = [
            'date' => $targetDate->format('Y-m-d'),
            'date_formatted' => $targetDate->format('d F Y'),
            'new_registrations' => Registration::whereDate('created_at', $targetDate)->count(),
            'successful_payments' => Payment::whereDate('paid_at', $targetDate)
                ->where('transaction_status', 'settlement')->count(),
            'total_revenue' => Payment::whereDate('paid_at', $targetDate)
                ->where('transaction_status', 'settlement')->sum('gross_amount'),
            'new_users' => User::whereDate('created_at', $targetDate)->count(),
            'new_submissions' => \App\Models\Submission::whereDate('created_at', $targetDate)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Get competition performance metrics
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompetitionMetrics()
    {
        $metrics = Competition::select('competitions.*')
            ->withCount([
                'registrations',
                'registrations as confirmed_count' => function($query) {
                    $query->where('status', 'confirmed');
                }
            ])
            ->withSum('registrations', 'amount')
            ->get()
            ->map(function($competition) {
                $conversionRate = $competition->registrations_count > 0 
                    ? ($competition->confirmed_count / $competition->registrations_count) * 100 
                    : 0;

                return [
                    'id' => $competition->id,
                    'name' => $competition->name,
                    'category' => $competition->category,
                    'total_registrations' => $competition->registrations_count,
                    'confirmed_registrations' => $competition->confirmed_count,
                    'conversion_rate' => round($conversionRate, 2),
                    'total_revenue' => $competition->registrations_sum_amount ?? 0,
                    'avg_revenue_per_participant' => $competition->confirmed_count > 0 
                        ? ($competition->registrations_sum_amount / $competition->confirmed_count) 
                        : 0,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $metrics
        ]);
    }
}
