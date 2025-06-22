<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PublicController extends Controller
{


    public function competitions()
    {
        // Get active competitions for main page
        $competitions = Competition::where('is_active', true)
            ->orderBy('registration_start', 'asc')
            ->paginate(12);

        // Get statistics for homepage display
        $stats = [
            'total_participants' => User::whereHas('roles', function($q) {
                $q->where('name', 'Peserta');
            })->count(),
            'active_competitions' => Competition::where('is_active', true)->count(),
            'total_universities' => User::whereNotNull('institution')->distinct('institution')->count(),
            'total_prizes' => Competition::where('is_active', true)->sum('prize_amount')
        ];

        return view('public.competitions', compact('competitions', 'stats'));
    }

    public function competition(Competition $competition)
    {
        // Only show active competitions to public
        if (!$competition->is_active) {
            abort(404);
        }

        $registrationsCount = $competition->registrations()
            ->where('status', 'confirmed')
            ->count();

        return view('public.competition', compact('competition', 'registrationsCount'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Here you would typically send an email
            // For now, we'll just return a success message
            // Mail::to('contact@unasfest.ac.id')->send(new ContactMail($request->all()));

            return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send message. Please try again later.');
        }
    }
}
