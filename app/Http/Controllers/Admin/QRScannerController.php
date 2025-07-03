<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * Scan QR Code and validate registration
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        try {
            $qrData = $request->qr_data;
            
            // Try to find registration by QR data
            // QR data could be registration number, ticket code, or registration ID
            $registration = $this->findRegistrationByQRData($qrData);
            
            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau tidak ditemukan'
                ], 404);
            }

            if ($registration->status !== 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Registrasi belum dikonfirmasi'
                ], 400);
            }

            // Check if already checked in
            if ($registration->checked_in_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peserta sudah melakukan check-in pada ' . $registration->checked_in_at->format('d/m/Y H:i:s'),
                    'data' => [
                        'registration' => $registration->load(['user', 'competition']),
                        'already_checked_in' => true
                    ]
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'QR Code valid',
                'data' => [
                    'registration' => $registration->load(['user', 'competition']),
                    'can_check_in' => true
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('QR Scanner Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses QR Code'
            ], 500);
        }
    }

    /**
     * Check-in participant
     */
    public function checkin(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id'
        ]);

        try {
            DB::beginTransaction();

            $registration = Registration::findOrFail($request->registration_id);

            if ($registration->status !== 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Registrasi belum dikonfirmasi'
                ], 400);
            }

            if ($registration->checked_in_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peserta sudah melakukan check-in'
                ], 400);
            }

            // Update check-in status
            $registration->update([
                'checked_in_at' => now(),
                'checked_in_by' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil',
                'data' => [
                    'registration' => $registration->load(['user', 'competition']),
                    'checked_in_at' => $registration->checked_in_at->format('d/m/Y H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Check-in Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat melakukan check-in'
            ], 500);
        }
    }

    /**
     * Get check-in history
     */
    public function history(Request $request)
    {
        $query = Registration::with(['user', 'competition'])
            ->whereNotNull('checked_in_at')
            ->orderBy('checked_in_at', 'desc');

        if ($request->has('competition_id') && $request->competition_id) {
            $query->where('competition_id', $request->competition_id);
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('checked_in_at', $request->date);
        }

        $registrations = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $registrations
        ]);
    }

    /**
     * Find registration by QR data
     */
    private function findRegistrationByQRData($qrData)
    {
        // Try different methods to find registration
        $registration = null;

        // Method 1: Direct registration ID
        if (is_numeric($qrData)) {
            $registration = Registration::find($qrData);
        }

        // Method 2: Registration number
        if (!$registration) {
            $registration = Registration::where('registration_number', $qrData)->first();
        }

        // Method 3: Ticket code
        if (!$registration) {
            $registration = Registration::where('ticket_code', $qrData)->first();
        }

        // Method 4: Try to extract from URL or complex QR data
        if (!$registration && str_contains($qrData, 'registration')) {
            // Extract ID from URL like: https://domain.com/registration/123
            preg_match('/registration[\/=](\d+)/', $qrData, $matches);
            if (isset($matches[1])) {
                $registration = Registration::find($matches[1]);
            }
        }

        return $registration;
    }
}
