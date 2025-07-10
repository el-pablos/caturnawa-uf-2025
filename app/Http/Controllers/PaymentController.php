<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Payment;
use App\Services\MidtransService;
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

    public function __construct()
    {
        // Only initialize MidtransService if configured
        if (env('MIDTRANS_SERVER_KEY') && env('MIDTRANS_CLIENT_KEY')) {
            $this->midtransService = app(MidtransService::class);
        } else {
            $this->midtransService = null;
            Log::warning('PaymentController initialized without Midtrans configuration');
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
     * @param \App\Models\Payment $payment
     * @return \Illuminate\View\View
     */
    public function status(Payment $payment)
    {
        $registration = $payment->registration;
        
        // Pastikan payment milik user yang sedang login
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('payment.status', compact('payment', 'registration'));
    }

    /**
     * Halaman sukses pembayaran
     *
     * @param \App\Models\Payment $payment
     * @return \Illuminate\View\View
     */
    public function finish(Payment $payment)
    {
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

        return view('payment.finish', compact('payment', 'registration'));
    }

    /**
     * Halaman pembayaran tidak selesai
     * 
     * @param \App\Models\Payment $payment
     * @return \Illuminate\View\View
     */
    public function unfinish(Payment $payment)
    {
        $registration = $payment->registration;
        
        return view('payment.unfinish', compact('payment', 'registration'));
    }

    /**
     * Halaman error pembayaran
     * 
     * @param \App\Models\Payment $payment
     * @return \Illuminate\View\View
     */
    public function error(Payment $payment)
    {
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

        // Pastikan payment milik user yang sedang login
        if ($registration->user_id !== Auth::id()) {
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
}
