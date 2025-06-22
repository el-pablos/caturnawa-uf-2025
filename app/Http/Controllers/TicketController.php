<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    public function verify($code)
    {
        $registration = Registration::where('ticket_code', $code)
            ->where('status', 'confirmed')
            ->with(['user', 'competition'])
            ->first();

        if (!$registration) {
            return view('ticket.verify', [
                'valid' => false,
                'message' => 'Invalid or expired ticket code'
            ]);
        }

        return view('ticket.verify', [
            'valid' => true,
            'registration' => $registration,
            'message' => 'Valid ticket'
        ]);
    }

    public function scan()
    {
        return view('ticket.scan');
    }

    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $registration = Registration::where('ticket_code', $request->code)
            ->where('status', 'confirmed')
            ->with(['user', 'competition'])
            ->first();

        if (!$registration) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired ticket code'
            ]);
        }

        return response()->json([
            'valid' => true,
            'registration' => [
                'id' => $registration->id,
                'registration_number' => $registration->registration_number,
                'participant_name' => $registration->user->name,
                'competition_name' => $registration->competition->name,
                'team_name' => $registration->team_name,
                'status' => $registration->status,
            ],
            'message' => 'Valid ticket'
        ]);
    }
}
