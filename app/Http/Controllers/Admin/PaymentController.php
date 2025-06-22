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
            'pending' => Payment::where('status', 'pending')->count(),
            'paid' => Payment::where('status', 'paid')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'cancelled' => Payment::where('status', 'cancelled')->count(),
            'total_amount' => Payment::where('status', 'paid')->sum('amount'),
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
     * Verifikasi pembayaran manual
     * 
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Payment $payment)
    {
        try {
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

            // Generate QR Code untuk tiket
            $payment->registration->generateQRCode();

            // Send confirmation email
            // TODO: Implement email notification

            DB::commit();

            return back()->with('success', 'Pembayaran berhasil diverifikasi dan registrasi dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tolak pembayaran
     * 
     * @param \App\Models\Payment $payment
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
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

            return back()->with('success', 'Pembayaran berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollback();
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
