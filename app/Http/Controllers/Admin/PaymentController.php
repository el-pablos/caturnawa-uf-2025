<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk mengelola pembayaran
 * 
 * Admin dapat melihat, memverifikasi, dan menolak pembayaran
 */
class PaymentController extends Controller
{
    /**
     * Tampilkan daftar pembayaran
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Payment::with(['registration.user', 'registration.competition'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search berdasarkan order ID atau nama user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('registration.user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->paginate(20);

        // Statistik
        $stats = [
            'total' => Payment::count(),
            'pending' => Payment::where('transaction_status', 'pending')->count(),
            'paid' => Payment::whereIn('transaction_status', ['settlement', 'capture'])->count(),
            'failed' => Payment::whereIn('transaction_status', ['deny', 'expire', 'failure'])->count(),
            'cancelled' => Payment::where('transaction_status', 'cancel')->count(),
            'total_amount' => Payment::whereIn('transaction_status', ['settlement', 'capture'])->sum('gross_amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * Tampilkan detail pembayaran
     * 
     * @param \App\Models\Payment $payment
     * @return \Illuminate\View\View
     */
    public function show(Payment $payment)
    {
        $payment->load(['registration.user', 'registration.competition']);
        
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Update payment status (general update method)
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Payment $payment)
    {
        try {
            // If no specific data is provided, default to verification
            if (!$request->has('status') && !$request->has('action')) {
                return $this->verify($payment);
            }

            // Handle different actions
            $action = $request->input('action');
            if ($action) {
                switch ($action) {
                    case 'verify':
                        return $this->verify($payment);
                    case 'confirm':
                        return $this->confirmPayment($payment);
                    case 'reject':
                        return $this->reject($request, $payment);
                }
            }

            // Handle manual status update
            $request->validate([
                'status' => 'required|in:pending,verified,confirmed,rejected',
                'notes' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();

            $payment->update([
                'status' => $request->status,
                'notes' => $request->notes,
                'verified_at' => $request->status === 'verified' ? now() : null,
                'verified_by' => $request->status === 'verified' ? auth()->id() : null,
            ]);

            // If confirming payment, also update registration status
            if ($request->status === 'confirmed' && $payment->registration) {
                $payment->registration->update([
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                    'confirmed_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully',
                'data' => $payment->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Konfirmasi pembayaran dan registrasi
     *
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function confirmPayment(Payment $payment)
    {
        try {
            DB::beginTransaction();

            // Check if payment is already confirmed to prevent race conditions
            $payment = Payment::where('id', $payment->id)
                             ->where('is_confirmed', false)
                             ->lockForUpdate()
                             ->first();

            if (!$payment) {
                DB::rollback();
                
                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pembayaran sudah dikonfirmasi oleh admin lain atau tidak ditemukan.'
                    ]);
                }

                return back()->with('error', 'Pembayaran sudah dikonfirmasi oleh admin lain atau tidak ditemukan.');
            }

            // Verify payment is in valid state for confirmation
            if (!$payment->isAwaitingConfirmation()) {
                DB::rollback();
                
                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pembayaran tidak dalam status yang dapat dikonfirmasi.'
                    ]);
                }

                return back()->with('error', 'Pembayaran tidak dalam status yang dapat dikonfirmasi.');
            }

            // Update payment confirmation
            $payment->update([
                'is_confirmed' => true,
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
            ]);

            // Update registration status to confirmed
            $payment->registration->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
            ]);

            // Generate QR Code untuk tiket
            $payment->registration->generateQRCode();

            // Send confirmation email
            // TODO: Implement email notification

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil dikonfirmasi dan registrasi disetujui.'
                ]);
            }

            return back()->with('success', 'Pembayaran berhasil dikonfirmasi dan registrasi disetujui.');
        } catch (\Exception $e) {
            DB::rollback();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage()
                ]);
            }

            return back()->with('error', 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Verifikasi pembayaran manual
     * 
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Payment $payment)
    {
        try {
            // Validate payment can be verified
            if ($payment->status === 'paid') {
                return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya.');
            }

            if (!$payment->registration) {
                return back()->with('error', 'Data registrasi tidak ditemukan.');
            }

            DB::beginTransaction();

            // Update payment status
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            // Update registration status
            $payment->registration->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
            ]);

            // Generate QR Code untuk tiket with better error handling
            try {
                $payment->registration->generateQRCode();
            } catch (\Exception $qrException) {
                // Log the QR code error but don't fail the entire verification
                \Log::error('Failed to generate QR Code for registration ' . $payment->registration->id . ': ' . $qrException->getMessage());
                // Continue with the verification process
            }

            // Send confirmation email
            // TODO: Implement email notification

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil diverifikasi dan registrasi dikonfirmasi.'
                ]);
            }

            return back()->with('success', 'Pembayaran berhasil diverifikasi dan registrasi dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollback();

            \Log::error('Payment verification failed for payment ID ' . $payment->id . ': ' . $e->getMessage());

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memverifikasi pembayaran: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tolak pembayaran
     * 
     * @param \App\Models\Payment $payment
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reject(Payment $payment, Request $request)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Update payment status
            $payment->update([
                'status' => 'failed',
                'rejection_reason' => $request->rejection_reason,
                'rejected_by' => auth()->id(),
                'rejected_at' => now(),
            ]);

            // Update registration status
            $payment->registration->update([
                'status' => 'pending',
            ]);

            // Send rejection email
            // TODO: Implement email notification

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil ditolak.'
                ]);
            }

            return back()->with('success', 'Pembayaran berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollback();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menolak pembayaran: ' . $e->getMessage()
                ]);
            }

            return back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Refund pembayaran
     * 
     * @param \App\Models\Payment $payment
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refund(Payment $payment, Request $request)
    {
        $request->validate([
            'refund_reason' => 'required|string|max:500',
            'refund_amount' => 'required|numeric|min:0|max:' . $payment->amount,
        ]);

        try {
            DB::beginTransaction();

            // Process refund through payment gateway
            // TODO: Implement actual refund logic with Midtrans

            // Update payment status
            $payment->update([
                'status' => 'refunded',
                'refund_amount' => $request->refund_amount,
                'refund_reason' => $request->refund_reason,
                'refunded_by' => auth()->id(),
                'refunded_at' => now(),
            ]);

            // Update registration status
            $payment->registration->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
            ]);

            DB::commit();

            return back()->with('success', 'Refund berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses refund: ' . $e->getMessage());
        }
    }

}
