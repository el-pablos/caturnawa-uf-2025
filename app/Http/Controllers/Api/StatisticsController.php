<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $data = [];

        // Role-specific statistics
        if ($user->hasRole(['Super Admin', 'Admin'])) {
            $data = $this->getAdminStatistics();
        } elseif ($user->hasRole('Juri')) {
            $data = $this->getJuriStatistics();
        } elseif ($user->hasRole('Peserta')) {
            $data = $this->getPesertaStatistics($user);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function competition(Competition $competition)
    {
        $registrations = $competition->registrations();
        
        $data = [
            'competition' => [
                'id' => $competition->id,
                'name' => $competition->name,
                'category' => $competition->category,
                'status' => $competition->status,
            ],
            'registrations' => [
                'total' => $registrations->count(),
                'confirmed' => $registrations->where('status', 'confirmed')->count(),
                'pending' => $registrations->where('status', 'pending')->count(),
                'cancelled' => $registrations->where('status', 'cancelled')->count(),
            ],
            'payments' => [
                'total_amount' => $registrations->whereHas('payment', function($q) {
                    $q->where('status', 'paid');
                })->with('payment')->get()->sum('payment.amount'),
                'paid' => Payment::whereHas('registration', function($q) use ($competition) {
                    $q->where('competition_id', $competition->id);
                })->where('status', 'paid')->count(),
                'pending' => Payment::whereHas('registration', function($q) use ($competition) {
                    $q->where('competition_id', $competition->id);
                })->where('status', 'pending')->count(),
            ],
            'daily_registrations' => $this->getDailyRegistrations($competition),
            'category_breakdown' => $this->getCategoryBreakdown($competition),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    private function getAdminStatistics()
    {
        return [
            'overview' => [
                'total_competitions' => Competition::count(),
                'active_competitions' => Competition::where('is_active', true)->count(),
                'total_registrations' => Registration::count(),
                'confirmed_registrations' => Registration::where('status', 'confirmed')->count(),
                'total_users' => User::count(),
                'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            ],
            'recent_registrations' => Registration::with(['user', 'competition'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($reg) {
                    return [
                        'id' => $reg->id,
                        'user_name' => $reg->user->name,
                        'competition_name' => $reg->competition->name,
                        'status' => $reg->status,
                        'created_at' => $reg->created_at->diffForHumans(),
                    ];
                }),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'competition_registrations' => $this->getCompetitionRegistrations(),
        ];
    }

    private function getJuriStatistics()
    {
        $user = auth()->user();
        
        // Get competitions where user is assigned as jury
        $competitionIds = DB::table('competition_juries')
            ->where('user_id', $user->id)
            ->pluck('competition_id');

        return [
            'overview' => [
                'assigned_competitions' => $competitionIds->count(),
                'total_submissions' => DB::table('submissions')
                    ->whereIn('registration_id', function($q) use ($competitionIds) {
                        $q->select('id')->from('registrations')
                          ->whereIn('competition_id', $competitionIds);
                    })->count(),
                'graded_submissions' => 0, // Will be implemented when scoring system is enhanced
                'pending_submissions' => 0, // Will be implemented when scoring system is enhanced
            ],
            'assigned_competitions' => Competition::whereIn('id', $competitionIds)
                ->with(['registrations' => function($q) {
                    $q->where('status', 'confirmed');
                }])
                ->get()
                ->map(function($comp) {
                    return [
                        'id' => $comp->id,
                        'name' => $comp->name,
                        'category' => $comp->category,
                        'participants' => $comp->registrations->count(),
                    ];
                }),
        ];
    }

    private function getPesertaStatistics($user)
    {
        $registrations = Registration::where('user_id', $user->id);
        
        return [
            'overview' => [
                'total_registrations' => $registrations->count(),
                'confirmed_registrations' => $registrations->where('status', 'confirmed')->count(),
                'pending_registrations' => $registrations->where('status', 'pending')->count(),
                'total_spent' => Payment::whereHas('registration', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->where('status', 'paid')->sum('amount'),
            ],
            'my_registrations' => $registrations->with(['competition', 'payment'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($reg) {
                    return [
                        'id' => $reg->id,
                        'registration_number' => $reg->registration_number,
                        'competition_name' => $reg->competition->name,
                        'status' => $reg->status,
                        'payment_status' => $reg->payment?->status ?? 'unpaid',
                        'created_at' => $reg->created_at->diffForHumans(),
                    ];
                }),
        ];
    }

    private function getDailyRegistrations(Competition $competition)
    {
        return Registration::where('competition_id', $competition->id)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->limit(30)
            ->get()
            ->map(function($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('Y-m-d'),
                    'count' => $item->count,
                ];
            });
    }

    private function getCategoryBreakdown(Competition $competition)
    {
        // This would be more meaningful if we had participant categories
        // For now, just return team vs individual breakdown
        $total = $competition->registrations()->count();
        $team = $competition->registrations()->whereNotNull('team_name')->count();
        $individual = $total - $team;

        return [
            ['label' => 'Team', 'count' => $team],
            ['label' => 'Individual', 'count' => $individual],
        ];
    }

    private function getMonthlyRevenue()
    {
        return Payment::where('status', 'paid')
            ->selectRaw('YEAR(paid_at) as year, MONTH(paid_at) as month, SUM(amount) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->map(function($item) {
                return [
                    'period' => sprintf('%04d-%02d', $item->year, $item->month),
                    'revenue' => $item->revenue,
                ];
            })
            ->reverse()
            ->values();
    }

    private function getCompetitionRegistrations()
    {
        return Competition::withCount(['registrations' => function($q) {
            $q->where('status', 'confirmed');
        }])
        ->orderBy('registrations_count', 'desc')
        ->limit(10)
        ->get()
        ->map(function($comp) {
            return [
                'name' => $comp->name,
                'registrations' => $comp->registrations_count,
            ];
        });
    }
}
