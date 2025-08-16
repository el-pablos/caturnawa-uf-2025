<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\User;
use App\Services\LeaderboardService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $leaderboardService;

    public function __construct(LeaderboardService $leaderboardService)
    {
        $this->leaderboardService = $leaderboardService;
    }

    /**
     * Display the home page with all sections
     */
    public function index()
    {
        // Get featured competitions (limit to 6 for homepage)
        $competitions = Competition::where('is_active', true)
            ->where('show_leaderboard', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get leaderboard data for each competition
        $competitionLeaderboards = [];
        foreach ($competitions as $competition) {
            $competitionLeaderboards[$competition->id] = $this->leaderboardService->getTopTeams($competition, 4);
        }

        // Get statistics (removed participant counts)
        $stats = [
            'total_competitions' => Competition::where('is_active', true)->count(),
            'total_prizes' => Competition::where('is_active', true)->sum('price'),
        ];

        return view('public.home', compact('competitions', 'stats', 'competitionLeaderboards'));
    }

    /**
     * Handle contact form submission
     */
    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Here you can implement email sending logic
        // For now, we'll just return success message

        return back()->with('success', 'Your message has been sent successfully. Our team will contact you soon.');
    }
}
