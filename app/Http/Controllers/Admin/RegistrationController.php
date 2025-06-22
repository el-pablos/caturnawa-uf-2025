<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RegistrationExport;

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
     * Konfirmasi registrasi
     * 
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Registration $registration)
    {
        try {
            DB::beginTransaction();

            $registration->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
            ]);

            // Generate QR Code untuk tiket
            $registration->generateQRCode();

            // Send notification email
            // TODO: Implement email notification

            DB::commit();

            return back()->with('success', 'Registrasi berhasil dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mengkonfirmasi registrasi: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan registrasi
     * 
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Registration $registration)
    {
        try {
            DB::beginTransaction();

            $registration->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
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
     * Export registrasi ke Excel
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request)
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

        return Excel::download(new RegistrationExport($registrations), 'registrations.xlsx');
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
}
