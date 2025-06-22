<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with all sections
     */
    public function index()
    {
        // Get featured competitions (limit to 6 for homepage)
        $competitions = Competition::where('status', 'active')
            ->where('is_published', true)
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get statistics
        $stats = [
            'total_competitions' => Competition::where('is_published', true)->count(),
            'total_participants' => Registration::where('status', 'confirmed')->count(),
            'total_universities' => User::whereNotNull('institution')
                ->distinct('institution')
                ->count('institution'),
            'total_prizes' => Competition::where('is_published', true)->sum('prize_pool'),
        ];

        return view('public.home', compact('competitions', 'stats'));
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

        return back()->with('success', 'Pesan Anda telah berhasil dikirim. Tim kami akan segera menghubungi Anda.');
    }
}
