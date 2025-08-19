<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controller untuk mengelola registrasi peserta
 * 
 * Admin dapat melihat, mengkonfirmasi, dan membatalkan registrasi
 */
class RegistrationController extends Controller
{
    /**
     * Tampilkan daftar registrasi
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Registration::with(['user', 'competition', 'payment'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kompetisi
        if ($request->filled('competition_id')) {
            $query->where('competition_id', $request->competition_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $registrations = $query->paginate(20);
        $competitions = Competition::orderBy('name')->get();

        // Statistik
        $stats = [
            'total' => Registration::count(),
            'pending' => Registration::where('status', 'pending')->count(),
            'confirmed' => Registration::where('status', 'confirmed')->count(),
            'cancelled' => Registration::where('status', 'cancelled')->count(),
        ];

        return view('admin.registrations.index', compact('registrations', 'competitions', 'stats'));
    }

    /**
     * Tampilkan detail registrasi
     * 
     * @param \App\Models\Registration $registration
     * @return \Illuminate\View\View
     */
    public function show(Registration $registration)
    {
        $registration->load(['user', 'competition', 'payment', 'submissions']);
        
        return view('admin.registrations.show', compact('registration'));
    }

    /**
     * Update registrasi (general update method)
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Registration $registration)
    {
        // Handle different actions based on request data
        $action = $request->input('action', 'confirm'); // Default to confirm

        switch ($action) {
            case 'confirm':
                return $this->confirm($registration);
            case 'cancel':
                return $this->cancel($registration);
            case 're-enable':
                return $this->reEnable($registration);
            default:
                // Default behavior is to confirm
                return $this->confirm($registration);
        }
    }

    /**
     * Konfirmasi registrasi
     *
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Registration $registration)
    {
        // Validasi sequential processing
        if (!$this->canProcessRegistration($registration, 'confirm')) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registrasi harus diproses secara berurutan. Pastikan registrasi sebelumnya sudah diproses terlebih dahulu.'
                ]);
            }
            return back()->with('error', 'Registrasi harus diproses secara berurutan. Pastikan registrasi sebelumnya sudah diproses terlebih dahulu.');
        }

        // Pastikan registrasi dalam status yang tepat
        if (!in_array($registration->status, ['pending', 'paid'])) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya registrasi dengan status pending atau paid yang dapat dikonfirmasi.'
                ]);
            }
            return back()->with('error', 'Hanya registrasi dengan status pending atau paid yang dapat dikonfirmasi.');
        }

        // DISABLED: Manual registration confirmation workflow has been disabled
        // Registrations are now automatically confirmed after successful payment

        /*
        try {
            DB::beginTransaction();

            $registration->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
            ]);

            // If registration has a payment, mark it as confirmed
            if ($registration->payment && $registration->payment->isSuccess()) {
                $registration->payment->update([
                    'is_confirmed' => true,
                    'confirmed_at' => now(),
                    'confirmed_by' => auth()->id(),
                    'confirmation_notes' => 'Pembayaran dikonfirmasi otomatis saat konfirmasi registrasi'
                ]);
            }

            // Generate QR Code untuk tiket
            $registration->generateQRCode();

            // Send notification email
            // TODO: Implement email notification

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registrasi berhasil dikonfirmasi.'
                ]);
            }
        */

        // Return message that feature is disabled
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Fitur konfirmasi registrasi manual telah dinonaktifkan. Registrasi dikonfirmasi otomatis setelah pembayaran berhasil.'
            ]);
        }

        return back()->with('info', 'Fitur konfirmasi registrasi manual telah dinonaktifkan. Registrasi dikonfirmasi otomatis setelah pembayaran berhasil.');
    }

    /**
     * Batalkan registrasi
     * 
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Registration $registration)
    {
        // Validasi sequential processing
        if (!$this->canProcessRegistration($registration, 'cancel')) {
            return back()->with('error', 'Registrasi harus diproses secara berurutan. Pastikan registrasi sebelumnya sudah diproses terlebih dahulu.');
        }

        // Pastikan registrasi dalam status yang tepat
        if (!in_array($registration->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Registrasi dengan status ini tidak dapat dibatalkan.');
        }

        try {
            DB::beginTransaction();

            $registration->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancelled_reason' => 'Dibatalkan oleh admin'
            ]);

            // Refund payment if exists
            if ($registration->payment && $registration->payment->status === 'paid') {
                // TODO: Implement refund logic
            }

            DB::commit();

            return back()->with('success', 'Registrasi berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal membatalkan registrasi: ' . $e->getMessage());
        }
    }

    /**
     * Validasi apakah registrasi dapat diproses secara sequential
     * 
     * @param \App\Models\Registration $registration
     * @param string $action
     * @return bool
     */
    private function canProcessRegistration(Registration $registration, $action)
    {
        // Allow processing based on individual registration criteria instead of sequential
        // Check if the registration meets the requirements for the specific action
        
        switch ($action) {
            case 'confirm':
                // Can confirm if status is pending or paid (paid means payment is successful)
                return in_array($registration->status, ['pending', 'paid']);
                
            case 'cancel':
                // Can cancel if not already cancelled or expired
                return !in_array($registration->status, ['cancelled', 'expired']);
                
            default:
                return true;
        }
    }



    /**
     * Export registrasi ke PDF
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $query = Registration::with(['user', 'competition', 'payment']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('competition_id')) {
            $query->where('competition_id', $request->competition_id);
        }

        $registrations = $query->get();

        $pdf = Pdf::loadView('admin.registrations.pdf', compact('registrations'));
        
        return $pdf->download('registrations.pdf');
    }

    /**
     * Re-enable registration untuk user yang sudah cancel
     *
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\JsonResponse
     */
    public function reEnable(Registration $registration)
    {
        try {
            if ($registration->status !== 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya registrasi yang dibatalkan yang dapat diaktifkan kembali.'
                ]);
            }

            DB::beginTransaction();

            $registration->update([
                'status' => 'pending',
                'cancelled_at' => null,
                'cancelled_by' => null,
                're_enabled_at' => now(),
                're_enabled_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil diaktifkan kembali. Peserta dapat mendaftar ulang.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan kembali registrasi: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Hapus registrasi secara permanen
     *
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Registration $registration)
    {
        try {
            DB::beginTransaction();

            // Check if registration has payment - only superadmin can delete paid registrations
            if ($registration->payment && $registration->payment->isSuccess()) {
                if (!auth()->user()->isSuperAdmin()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Registrasi dengan pembayaran yang sudah berhasil hanya dapat dihapus oleh Super Admin.'
                    ]);
                }
            }

            // Delete related data
            if ($registration->payment) {
                $registration->payment->delete();
            }

            if ($registration->submissions) {
                $registration->submissions()->delete();
            }

            $registration->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil dihapus secara permanen.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus registrasi: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Handle generic export requests
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        return $this->exportPdf($request);
    }

    /**
     * Lock a registration
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\JsonResponse
     */
    public function lock(Request $request, Registration $registration)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        try {
            $registration->lock(
                $request->reason ?? 'Locked by admin',
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Registration has been locked successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to lock registration: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Unlock a registration
     *
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlock(Registration $registration)
    {
        try {
            $registration->unlock();

            return response()->json([
                'success' => true,
                'message' => 'Registration has been unlocked successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unlock registration: ' . $e->getMessage()
            ]);
        }
    }
}
