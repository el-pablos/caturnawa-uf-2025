<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $registrations = Registration::with(['competition', 'payment'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peserta.registrations.index', compact('registrations'));
    }

    public function show(Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to registration');
        }

        $registration->load(['competition', 'payment', 'teamMembers']);

        return view('peserta.registrations.show', compact('registration'));
    }

    public function update(Request $request, Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to registration');
        }

        // Only allow updates for pending registrations
        if ($registration->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot update confirmed or cancelled registration');
        }

        $request->validate([
            'team_name' => 'nullable|string|max:255',
            'team_members' => 'nullable|array',
            'team_members.*.name' => 'required|string|max:255',
            'team_members.*.email' => 'required|email|max:255',
            'team_members.*.phone' => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        
        try {
            // Update registration
            $registration->update([
                'team_name' => $request->team_name,
                'updated_at' => now(),
            ]);

            // Update team members if provided
            if ($request->has('team_members') && $registration->competition->is_team) {
                // Remove existing team members
                $registration->teamMembers()->delete();
                
                // Add new team members
                foreach ($request->team_members as $member) {
                    $registration->teamMembers()->create([
                        'name' => $member['name'],
                        'email' => $member['email'],
                        'phone' => $member['phone'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('peserta.registrations.show', $registration)
                ->with('success', 'Registration updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update registration: ' . $e->getMessage());
        }
    }

    public function cancel(Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to registration');
        }

        // Only allow cancellation for pending registrations
        if ($registration->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot cancel confirmed registration');
        }

        DB::beginTransaction();
        
        try {
            $registration->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // If there's a pending payment, cancel it too
            if ($registration->payment && $registration->payment->status === 'pending') {
                $registration->payment->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('peserta.registrations.index')
                ->with('success', 'Registration cancelled successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to cancel registration: ' . $e->getMessage());
        }
    }

    public function ticket(Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to registration');
        }

        // Only confirmed registrations have tickets
        if ($registration->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Ticket is only available for confirmed registrations');
        }

        return view('peserta.registrations.ticket', compact('registration'));
    }
}
