<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QRScannerController extends Controller
{
    /**
     * Display QR Scanner page
     */
    public function index()
    {
        return view('admin.qr-scanner.index');
    }

    /**
     * Verify QR Code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        try {
            $qrData = json_decode($request->qr_data, true);

            if (!$qrData || !isset($qrData['type']) || $qrData['type'] !== 'unas_fest_ticket') {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau bukan untuk UNAS Fest'
                ]);
            }

            $registration = Registration::where('ticket_code', $qrData['ticket_code'])
                ->where('registration_number', $qrData['registration_number'])
                ->with(['user', 'competition', 'payment'])
                ->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket tidak ditemukan dalam sistem'
                ]);
            }

            if ($registration->status !== 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket belum dikonfirmasi atau tidak valid',
                    'status' => $registration->status
                ]);
            }

            if (!$registration->payment || !$registration->payment->isSuccess()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran belum dikonfirmasi'
                ]);
            }

            // Check if already checked in
            if ($registration->checked_in_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peserta sudah melakukan check-in',
                    'checked_in_at' => $registration->checked_in_at->format('d M Y H:i:s'),
                    'registration' => $this->formatRegistrationData($registration)
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'QR Code valid',
                'registration' => $this->formatRegistrationData($registration)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error memproses QR Code: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check-in participant
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id'
        ]);

        try {
            DB::beginTransaction();

            $registration = Registration::findOrFail($request->registration_id);

            if ($registration->checked_in_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peserta sudah melakukan check-in sebelumnya'
                ]);
            }

            $registration->update([
                'checked_in_at' => now(),
                'checked_in_by' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil',
                'checked_in_at' => $registration->checked_in_at->format('d M Y H:i:s')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error saat check-in: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get check-in history
     */
    public function history()
    {
        $checkedInRegistrations = Registration::whereNotNull('checked_in_at')
            ->with(['user', 'competition'])
            ->orderBy('checked_in_at', 'desc')
            ->paginate(20);

        return view('admin.qr-scanner.history', compact('checkedInRegistrations'));
    }

    /**
     * Format registration data for response
     */
    private function formatRegistrationData($registration)
    {
        return [
            'id' => $registration->id,
            'registration_number' => $registration->registration_number,
            'ticket_code' => $registration->ticket_code,
            'participant_name' => $registration->user->name,
            'participant_email' => $registration->user->email,
            'competition_name' => $registration->competition->name,
            'competition_category' => $registration->competition->category,
            'institution' => $registration->institution,
            'phone' => $registration->phone ?: $registration->user->phone,
            'registered_at' => $registration->registered_at->format('d M Y H:i:s'),
            'confirmed_at' => $registration->confirmed_at?->format('d M Y H:i:s'),
            'checked_in_at' => $registration->checked_in_at?->format('d M Y H:i:s'),
            'team_members' => $registration->team_members,
            'payment_status' => $registration->payment?->status_label
        ];
    }
}
