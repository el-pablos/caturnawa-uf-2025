<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Payment;
use App\Models\Registration;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;
use Dompdf\Options;
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
            !$user->hasRole(['superadmin', 'admin'])) {
            abort(403, 'Unauthorized access to invoice');
        }

        // Only allow download for paid payments
        if ($payment->status !== 'paid') {
            return redirect()->back()->with('error', 'Invoice only available for paid payments');
        }

        try {
            // Load related data
            $payment->load(['registration.competition', 'registration.user', 'registration.teamMembers']);

            $data = [
                'payment' => $payment,
                'registration' => $payment->registration,
                'generated_at' => now(),
            ];

            // Generate PDF using DomPDF directly
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('dpi', 150);
            $options->set('chroot', public_path());

            $dompdf = new Dompdf($options);

            // Render the view to HTML
            $html = view('downloads.invoice-new', $data)->render();

            // Load HTML into DomPDF
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'invoice-' . $payment->order_id . '.pdf';

            // Return PDF download response
            return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            Log::error('Error generating PDF invoice: ' . $e->getMessage());
            Log::error('PDF Invoice Error Stack Trace: ' . $e->getTraceAsString());

            // Fallback to HTML view if PDF generation fails
            $data = [
                'payment' => $payment->load(['registration.competition', 'registration.user', 'registration.teamMembers']),
                'registration' => $payment->registration,
                'generated_at' => now(),
            ];

            return view('downloads.invoice-new', $data)->with('error', 'PDF generation failed, showing HTML version. Error: ' . $e->getMessage());
        }
    }

    public function ticket(Registration $registration)
    {
        // Check permission
        $user = Auth::user();

        if ($registration->user_id !== $user->id &&
            !$user->hasRole(['superadmin', 'admin'])) {
            abort(403, 'Unauthorized access to ticket');
        }

        // Only confirmed registrations have tickets
        if ($registration->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Ticket only available for confirmed registrations');
        }

        try {
            // Generate ticket with QR code
            $registration->load(['competition', 'user', 'teamMembers']);

            $data = [
                'registration' => $registration,
                'generated_at' => now(),
            ];

            // Generate PDF using DomPDF directly
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('dpi', 150);
            $options->set('chroot', public_path());

            $dompdf = new Dompdf($options);

            // Render the view to HTML
            $html = view('downloads.ticket', $data)->render();

            // Load HTML into DomPDF
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'ticket-' . $registration->id . '.pdf';

            // Return PDF download response
            return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            Log::error('Error generating PDF ticket: ' . $e->getMessage());
            Log::error('PDF Ticket Error Stack Trace: ' . $e->getTraceAsString());

            // Fallback to HTML view if PDF generation fails
            return view('downloads.ticket', compact('registration'))->with('error', 'PDF generation failed, showing HTML version. Error: ' . $e->getMessage());
        }
    }

    /**
     * Unified invoice download for all roles except juri
     *
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\Response
     */
    public function unifiedInvoice(Registration $registration)
    {
        $user = Auth::user();

        // Check if user is juri - they cannot download invoices
        if ($user->isJuri()) {
            abort(403, 'Juri tidak memiliki akses untuk mengunduh invoice.');
        }

        // Check permission based on role
        $hasPermission = false;

        if ($user->isSuperAdmin() || $user->isAdmin() || $user->isFinance()) {
            // Admin roles can download any invoice
            $hasPermission = true;
        } elseif ($user->isPeserta()) {
            // Peserta can only download their own invoice
            $hasPermission = ($registration->user_id === $user->id);
        }

        if (!$hasPermission) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh invoice ini.');
        }

        // Check if registration has payment and is paid
        if (!$registration->payment || $registration->payment->status !== 'paid') {
            return redirect()->back()->with('error', 'Invoice hanya tersedia untuk pendaftaran yang sudah dibayar.');
        }

        try {
            // Use InvoiceService for consistent invoice generation
            $invoiceService = app(InvoiceService::class);
            return $invoiceService->downloadInvoice($registration);

        } catch (\Exception $e) {
            Log::error('Error generating unified invoice: ' . $e->getMessage());
            Log::error('Unified Invoice Error Stack Trace: ' . $e->getTraceAsString());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh invoice: ' . $e->getMessage());
        }
    }
}
