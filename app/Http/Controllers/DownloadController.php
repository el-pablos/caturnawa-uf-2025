<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DownloadController extends Controller
{
    public function submission(Submission $submission, $filename)
    {
        // Check permission
        $user = Auth::user();
        
        // Allow access if user owns the submission or is admin/jury
        if ($submission->registration->user_id !== $user->id && 
            !$user->hasRole(['Super Admin', 'Admin', 'Juri'])) {
            abort(403, 'Unauthorized access to file');
        }

        // Find file in submission files
        $files = $submission->files ?? [];
        $file = collect($files)->firstWhere('filename', $filename);

        if (!$file) {
            return back()->with('error', 'File tidak ditemukan dalam submission');
        }

        $filePath = $file['path'];
        
        if (!Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan di storage. Silakan upload ulang file Anda.');
        }

        try {
            // Check file size and mime type for security
            $fileSize = Storage::disk('public')->size($filePath);
            if ($fileSize > 50 * 1024 * 1024) { // 50MB limit
                return back()->with('error', 'File terlalu besar untuk didownload');
            }

            return Storage::disk('public')->download($filePath, $file['original_name']);
            
        } catch (\Exception $e) {
            \Log::error('Download error for submission ' . $submission->id . ': ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunduh file');
        }
    }

    public function invoice(Payment $payment)
    {
        // Check permission
        $user = Auth::user();
        
        if ($payment->registration->user_id !== $user->id && 
            !$user->hasRole(['Super Admin', 'Admin'])) {
            abort(403, 'Unauthorized access to invoice');
        }

        // Only allow download for paid payments
        if ($payment->status !== 'paid') {
            return redirect()->back()->with('error', 'Invoice only available for paid payments');
        }

        // Generate invoice PDF (placeholder implementation)
        // In a real implementation, you would generate a proper PDF invoice
        $data = [
            'payment' => $payment->load(['registration.competition', 'registration.user']),
            'generated_at' => now(),
        ];

        // For now, return a view that can be printed/saved as PDF
        return view('downloads.invoice', $data);
    }

    public function ticket(Registration $registration)
    {
        // Check permission
        $user = Auth::user();
        
        if ($registration->user_id !== $user->id && 
            !$user->hasRole(['Super Admin', 'Admin'])) {
            abort(403, 'Unauthorized access to ticket');
        }

        // Only confirmed registrations have tickets
        if ($registration->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Ticket only available for confirmed registrations');
        }

        // Generate ticket with QR code
        $registration->load(['competition', 'user']);
        
        return view('downloads.ticket', compact('registration'));
    }
}
