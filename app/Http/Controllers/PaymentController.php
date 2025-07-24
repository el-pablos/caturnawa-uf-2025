<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Payment;
use App\Services\MidtransService;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controller untuk Payment Gateway dengan Midtrans
 * 
 * Menangani proses pembayaran, callback, dan status pembayaran
 */
class PaymentController extends Controller
{
    protected $midtransService;
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
        
        // Only initialize MidtransService if configured
        if (config('midtrans.server_key') && config('midtrans.client_key')) {
            $this->midtransService = app(MidtransService::class);
            Log::info('PaymentController initialized with Midtrans configuration');
        } else {
            $this->midtransService = null;
            Log::warning('PaymentController initialized without Midtrans configuration', [
                'server_key_exists' => !empty(config('midtrans.server_key')),
                'client_key_exists' => !empty(config('midtrans.client_key')),
                'environment' => config('app.env', 'unknown')
            ]);
        }
    }

    /**
     * Tampilkan halaman checkout
     * 
     * @param \App\Models\Registration $registration
     * @return \Illuminate\View\View
     */
    public function checkout(Registration $registration)
    {
        // Pastikan registration milik user yang sedang login
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // Pastikan registration masih pending
        if ($registration->status !== 'pending') {
            return redirect()->route('peserta.registrations.show', $registration)
                ->with('error', 'Pendaftaran ini sudah diproses.');
        }

        // Check if Midtrans is configured before showing checkout
        if (!$this->midtransService) {
            Log::warning('Checkout accessed without Midtrans configuration', [
                'registration_id' => $registration->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('peserta.registrations.show', $registration)
                ->with('error', 'Payment gateway sedang dalam perbaikan. Silakan coba lagi nanti atau hubungi administrator.');
        }

        $payment = Payment::where('registration_id', $registration->id)->first();

        return view('payment.checkout', compact('registration', 'payment'));
    }

    /**
     * Proses pembayaran dengan Midtrans
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(Request $request, Registration $registration)
    {
        // Log request details for debugging
        Log::info('Payment process started', [
            'registration_id' => $registration->id,
            'user_id' => Auth::id(),
            'request_method' => $request->method(),
            'request_headers' => $request->headers->all(),
            'request_data' => $request->all(),
            'expects_json' => $request->expectsJson(),
            'is_ajax' => $request->ajax(),
        ]);

        // Check if Midtrans is configured
        if (!$this->midtransService) {
            Log::error('Midtrans service not configured');
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway tidak dikonfigurasi. Silakan hubungi administrator.'
            ], 503);
        }

        // Pastikan registration milik user yang sedang login
        if ($registration->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);
        }

        // Pastikan registration masih pending
        if ($registration->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran ini sudah diproses.'
            ], 400);
        }

        // Validasi amount
        if ($registration->amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah pembayaran tidak valid.'
            ], 400);
        }

        // Validasi competition masih aktif
        if (!$registration->competition->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Kompetisi ini sudah tidak aktif.'
            ], 400);
        }

        // Get selected payment method
        $paymentMethod = $request->input('payment_method');

        try {
            // Use specific QRIS method for QRIS payments
            if (strtolower($paymentMethod) === 'qris') {
                $result = $this->midtransService->createQrisTransaction($registration);
            } else {
                $result = $this->midtransService->createTransaction($registration, $paymentMethod);
            }

            if ($result['success']) {
                $response = [
                    'success' => true,
                    'snap_token' => $result['snap_token'],
                    'redirect_url' => $result['redirect_url']
                ];

                // Add order_id for QRIS debugging
                if (isset($result['order_id'])) {
                    $response['order_id'] = $result['order_id'];
                }

                Log::info('Payment process success', $response);
                return response()->json($response);
            } else {
                $response = [
                    'success' => false,
                    'message' => $result['message']
                ];
                Log::error('Payment process failed', $response);
                return response()->json($response);
            }
        } catch (\Exception $e) {
            Log::error('Payment process error: ' . $e->getMessage(), [
                'registration_id' => $registration->id,
                'user_id' => Auth::id(),
                'payment_method' => $paymentMethod,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tampilkan status pembayaran
     *
     * @param int $paymentId
     * @return \Illuminate\View\View
     */
    public function status($paymentId)
    {
        // Find payment with proper error handling
        $payment = Payment::find($paymentId);

        if (!$payment) {
            Log::warning('Payment status accessed with non-existent payment ID', [
                'payment_id' => $paymentId,
                'user_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            return view('payment.not-found', [
                'message' => 'Data pembayaran tidak ditemukan.',
                'payment_id' => $paymentId
            ]);
        }

        $registration = $payment->registration;

        // Pastikan payment milik user yang sedang login
        if ($registration->user_id !== Auth::id()) {
            Log::warning('Unauthorized payment status access attempt', [
                'payment_id' => $paymentId,
                'payment_user_id' => $registration->user_id,
                'accessing_user_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            abort(403, 'Akses ditolak.');
        }

        // Always check payment status from Midtrans to ensure latest status
        if ($this->midtransService && $payment->order_id) {
            try {
                Log::info('Checking payment status from Midtrans', [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'current_status' => $payment->transaction_status
                ]);

                $result = $this->midtransService->checkTransactionStatus($payment->order_id);
                if ($result['success']) {
                    $data = $result['data'];
                    if (is_object($data)) {
                        $data = json_decode(json_encode($data), true);
                    }

                    Log::info('Midtrans status check result', [
                        'payment_id' => $payment->id,
                        'midtrans_status' => $data['transaction_status'] ?? 'unknown',
                        'current_db_status' => $payment->transaction_status
                    ]);

                    // Update payment from Midtrans response
                    $payment->updateFromMidtrans($data);
                    $payment->refresh(); // Refresh model to get updated data

                    Log::info('Payment status updated', [
                        'payment_id' => $payment->id,
                        'new_status' => $payment->transaction_status,
                        'is_success' => $payment->isSuccess()
                    ]);
                } else {
                    Log::warning('Failed to check payment status from Midtrans', [
                        'payment_id' => $payment->id,
                        'order_id' => $payment->order_id,
                        'error' => $result['message'] ?? 'Unknown error'
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error checking payment status from Midtrans', [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            Log::warning('Cannot check payment status - missing service or order_id', [
                'payment_id' => $payment->id,
                'has_midtrans_service' => !is_null($this->midtransService),
                'has_order_id' => !empty($payment->order_id)
            ]);
        }

        return view('payment.status', compact('payment', 'registration'));
    }

    /**
     * Update payment method for existing registration
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePaymentMethod(Request $request, Registration $registration)
    {
        // Log request details for debugging
        Log::info('Payment method update started', [
            'registration_id' => $registration->id,
            'user_id' => Auth::id(),
            'new_payment_method' => $request->input('payment_method'),
        ]);

        // Validate request
        $request->validate([
            'payment_method' => 'required|string|in:credit_card,bank_transfer,gopay,shopeepay,qris,ewallet'
        ]);

        // Check if user owns this registration
        if ($registration->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);
        }

        // Check if registration is still pending
        if ($registration->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran ini sudah diproses.'
            ], 400);
        }

        $paymentMethod = $request->input('payment_method');

        // Check if Midtrans is configured
        if (!$this->midtransService) {
            Log::error('Payment method update failed - Midtrans not configured', [
                'registration_id' => $registration->id,
                'user_id' => Auth::id(),
                'server_key_exists' => !empty(env('MIDTRANS_SERVER_KEY')),
                'client_key_exists' => !empty(env('MIDTRANS_CLIENT_KEY')),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment gateway tidak dikonfigurasi. Silakan hubungi administrator.',
                'error_code' => 'MIDTRANS_NOT_CONFIGURED'
            ], 503);
        }

        try {
            // Delete existing payment to create new one with updated method
            Payment::where('registration_id', $registration->id)->delete();

            // Create new transaction with selected payment method
            if (strtolower($paymentMethod) === 'qris') {
                $result = $this->midtransService->createQrisTransaction($registration);
            } else {
                $result = $this->midtransService->createTransaction($registration, $paymentMethod);
            }

            if ($result['success']) {
                $response = [
                    'success' => true,
                    'snap_token' => $result['snap_token'],
                    'payment_method' => $paymentMethod,
                    'message' => 'Metode pembayaran berhasil diperbarui.'
                ];

                Log::info('Payment method update success', $response);
                return response()->json($response);
            } else {
                $response = [
                    'success' => false,
                    'message' => $result['message']
                ];
                Log::error('Payment method update failed', $response);
                return response()->json($response);
            }
        } catch (\Exception $e) {
            Log::error('Payment method update error: ' . $e->getMessage(), [
                'registration_id' => $registration->id,
                'user_id' => Auth::id(),
                'payment_method' => $paymentMethod,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui metode pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman sukses pembayaran
     *
     * @param int|Payment $payment
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function finish($payment)
    {
        // Handle both Payment model and payment ID
        if (!$payment instanceof Payment) {
            $paymentId = $payment;
            $payment = Payment::find($paymentId);

            if (!$payment) {
                Log::warning('Payment finish accessed with non-existent payment ID', [
                    'payment_id' => $paymentId,
                    'user_id' => Auth::id(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);

                // Check if user has any payments and redirect to the latest one
                if (Auth::check()) {
                    $latestPayment = Payment::whereHas('registration', function($q) {
                        $q->where('user_id', Auth::id());
                    })->latest()->first();

                    if ($latestPayment) {
                        return redirect()->route('payment.finish', $latestPayment->id)
                            ->with('warning', 'Payment ID yang Anda akses tidak ditemukan. Anda dialihkan ke pembayaran terbaru Anda.');
                    }
                }

                return view('payment.not-found', [
                    'payment_id' => $paymentId,
                    'message' => 'Payment not found. The payment ID you are looking for does not exist.',
                    'available_payments' => Auth::check() ?
                        Payment::whereHas('registration', function($q) {
                            $q->where('user_id', Auth::id());
                        })->get(['id', 'order_id', 'status']) :
                        collect()
                ]);
            }
        }

        // Check if user has permission to view this payment
        if (Auth::check() && $payment->registration->user_id !== Auth::id() && !Auth::user()->hasRole(['superadmin', 'admin'])) {
            Log::warning('Unauthorized payment finish access attempt', [
                'payment_id' => $payment->id,
                'payment_user_id' => $payment->registration->user_id,
                'accessing_user_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            abort(403, 'Unauthorized access to payment information');
        }

        $registration = $payment->registration;

        // Force check status dari Midtrans untuk memastikan status terbaru
        if ($this->midtransService) {
            try {
                $result = $this->midtransService->checkTransactionStatus($payment->order_id);
                if ($result['success']) {
                    // Convert object to array if needed
                    $data = $result['data'];
                    if (is_object($data)) {
                        $data = json_decode(json_encode($data), true);
                    }
                    $payment->updateFromMidtrans($data);
                }
            } catch (\Exception $e) {
                Log::error('Error checking payment status on finish: ' . $e->getMessage());
            }
        }

        // MODIFIED: Skip admin confirmation check - payments are auto-confirmed
        // Show success page directly for successful payments
        /*
        // If payment is successful but not confirmed by admin, show invoice page
        if ($payment->isSuccess() && !$payment->is_confirmed) {
            return view('payment.invoice', compact('payment', 'registration'));
        }
        */

        // If payment is successful and confirmed, show success page
        if ($payment->isSuccess() && $payment->is_confirmed) {
            // Add contact WhatsApp fallback if not set
            if (!$registration->competition->contact_person_whatsapp) {
                $registration->competition->contact_person_whatsapp = $this->getDefaultContactWhatsApp($registration->competition);
            }

            return view('payment.finish-simple', compact('payment', 'registration'));
        }

        // For other statuses, redirect to appropriate page
        if ($payment->isPending()) {
            return redirect()->route('payment.status', $payment);
        }

        // For failed payments, redirect to error page
        return redirect()->route('payment.error', $payment);
    }

    /**
     * Halaman pembayaran tidak selesai
     *
     * @param int|Payment $payment
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function unfinish($payment)
    {
        // Handle both Payment model and payment ID
        if (!$payment instanceof Payment) {
            $paymentId = $payment;
            $payment = Payment::find($paymentId);

            if (!$payment) {
                Log::warning('Payment unfinish accessed with non-existent payment ID', [
                    'payment_id' => $paymentId,
                    'user_id' => Auth::id(),
                    'ip' => request()->ip()
                ]);

                return view('payment.not-found', [
                    'payment_id' => $paymentId,
                    'message' => 'Payment not found. The payment ID you are looking for does not exist.',
                    'available_payments' => Auth::check() ?
                        Payment::whereHas('registration', function($q) {
                            $q->where('user_id', Auth::id());
                        })->get(['id', 'order_id', 'status']) :
                        collect()
                ]);
            }
        }

        // Check permission
        if (Auth::check() && $payment->registration->user_id !== Auth::id() && !Auth::user()->hasRole(['superadmin', 'admin'])) {
            abort(403, 'Unauthorized access to payment information');
        }

        $registration = $payment->registration;

        return view('payment.unfinish', compact('payment', 'registration'));
    }

    /**
     * Halaman error pembayaran
     *
     * @param int|Payment $payment
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function error($payment)
    {
        // Handle both Payment model and payment ID
        if (!$payment instanceof Payment) {
            $paymentId = $payment;
            $payment = Payment::find($paymentId);

            if (!$payment) {
                Log::warning('Payment error accessed with non-existent payment ID', [
                    'payment_id' => $paymentId,
                    'user_id' => Auth::id(),
                    'ip' => request()->ip()
                ]);

                return view('payment.not-found', [
                    'payment_id' => $paymentId,
                    'message' => 'Payment not found. The payment ID you are looking for does not exist.',
                    'available_payments' => Auth::check() ?
                        Payment::whereHas('registration', function($q) {
                            $q->where('user_id', Auth::id());
                        })->get(['id', 'order_id', 'status']) :
                        collect()
                ]);
            }
        }

        // Check permission
        if (Auth::check() && $payment->registration->user_id !== Auth::id() && !Auth::user()->hasRole(['superadmin', 'admin'])) {
            abort(403, 'Unauthorized access to payment information');
        }

        $registration = $payment->registration;

        return view('payment.error', compact('payment', 'registration'));
    }

    /**
     * Handle notifikasi dari Midtrans
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function notification(Request $request)
    {
        // Check if Midtrans is configured
        if (!$this->midtransService) {
            Log::warning('Midtrans notification received but service not configured');
            return response()->json(['status' => 'error', 'message' => 'Service not configured'], 503);
        }

        try {
            $notification = $request->all();

            Log::info('Midtrans notification received', $notification);

            $result = $this->midtransService->handleNotification($notification);

            if ($result['success']) {
                return response()->json(['status' => 'ok']);
            } else {
                Log::error('Midtrans notification error: ' . $result['message']);
                return response()->json(['status' => 'error'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans notification exception: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Cek status transaksi dari Midtrans
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatus(Request $request)
    {
        // Check if Midtrans is configured
        if (!$this->midtransService) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway tidak dikonfigurasi.'
            ], 503);
        }

        $orderId = $request->input('order_id');

        if (!$orderId) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID diperlukan.'
            ]);
        }

        try {
            $result = $this->midtransService->checkTransactionStatus($orderId);
            
            if ($result['success']) {
                $payment = Payment::where('order_id', $orderId)->first();
                
                if ($payment) {
                    // Update payment dengan status terbaru
                    $data = $result['data'];
                    if (is_object($data)) {
                        $data = json_decode(json_encode($data), true);
                    }
                    $payment->updateFromMidtrans($data);

                    return response()->json([
                        'success' => true,
                        'data' => [
                            'transaction_status' => $payment->transaction_status,
                            'status_label' => $payment->status_label,
                            'payment_type' => $payment->payment_type,
                            'payment_method' => $payment->payment_method,
                        ]
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment tidak ditemukan.'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Check status error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengecek status.'
            ]);
        }
    }

    /**
     * Cancel pembayaran
     * 
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Payment $payment)
    {
        $registration = $payment->registration;
        
        // Pastikan payment milik user yang sedang login
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // Hanya bisa cancel jika status pending
        if ($payment->transaction_status !== 'pending') {
            return back()->with('error', 'Pembayaran tidak dapat dibatalkan.');
        }

        try {
            $result = $this->midtransService->cancelTransaction($payment->order_id);
            
            if ($result['success']) {
                $payment->update(['transaction_status' => 'cancel']);
                $registration->cancel();
                
                return redirect()->route('peserta.registrations.index')
                    ->with('success', 'Pembayaran berhasil dibatalkan.');
            } else {
                return back()->with('error', 'Gagal membatalkan pembayaran: ' . $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Cancel payment error: ' . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan saat membatalkan pembayaran.');
        }
    }

    /**
     * Download PDF struk pembayaran
     *
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function downloadReceipt(Payment $payment)
    {
        $registration = $payment->registration;

        // Pastikan payment milik user yang sedang login atau user adalah admin/superadmin
        if ($registration->user_id !== Auth::id() && !Auth::user()->hasRole(['superadmin', 'admin'])) {
            abort(403, 'Akses ditolak.');
        }

        // Pastikan payment sudah berhasil
        if (!$payment->isSuccess()) {
            return back()->with('error', 'Struk hanya tersedia untuk pembayaran yang berhasil.');
        }

        try {
            // Load view dengan data yang diperlukan
            $pdf = Pdf::loadView('pdf.payment-receipt', compact('payment', 'registration'));

            // Set paper dan options
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 150,
                'defaultPaperSize' => 'A4',
                'chroot' => public_path(),
            ]);

            $filename = 'struk-pembayaran-' . $payment->order_id . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Error generating PDF receipt: ' . $e->getMessage());
            Log::error('PDF Error Stack Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat membuat struk PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate and download invoice PDF
     *
     * @param \App\Models\Registration $registration
     * @return \Illuminate\Http\Response
     */
    public function invoice(Registration $registration)
    {
        // Check if user owns this registration
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to invoice.');
        }

        // Check if registration is paid
        if ($registration->status !== 'paid') {
            return back()->with('error', 'Invoice hanya tersedia untuk pendaftaran yang sudah dibayar.');
        }

        try {
            return $this->invoiceService->streamInvoice($registration);
        } catch (\Exception $e) {
            Log::error('Invoice generation error: ' . $e->getMessage(), [
                'registration_id' => $registration->id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat membuat invoice: ' . $e->getMessage());
        }
    }

    /**
     * Get default contact WhatsApp based on competition
     *
     * @param \App\Models\Competition $competition
     * @return string
     */
    private function getDefaultContactWhatsApp($competition): string
    {
        // Default contact person WhatsApp
        $defaultWhatsApp = '6281234567890'; // Remove + and - for WhatsApp format

        // Competition-specific contacts
        $contacts = [
            'kdbi' => '6281211111111',
            'edc' => '6281222222222',
            'short-movie' => '6281233333333',
            'fotografi' => '6281244444444',
            'lkti' => '6281255555555',
        ];

        $slug = \Str::slug($competition->name);
        foreach ($contacts as $key => $whatsapp) {
            if (\Str::contains($slug, $key)) {
                return $whatsapp;
            }
        }

        return $defaultWhatsApp;
    }
}
