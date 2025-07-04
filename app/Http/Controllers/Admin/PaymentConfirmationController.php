<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentConfirmationController extends Controller
{
    /**
     * Display pending payment confirmations
     */
    public function index(Request $request)
    {
        $query = Payment::with(['registration.user', 'registration.competition']);

        // Filter by confirmation status
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('status', 'success')->where('is_confirmed', false);
            } elseif ($request->status === 'confirmed') {
                $query->where('is_confirmed', true);
            } elseif ($request->status === 'failed') {
                $query->where('status', 'failed');
            }
        }

        // Filter by competition
        if ($request->filled('competition_id')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('competition_id', $request->competition_id);
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('registration.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get competitions for filter
        $competitions = \App\Models\Competition::where('is_active', true)->get();

        // Statistics
        $stats = [
            'total' => Payment::where('status', 'success')->count(),
            'pending' => Payment::where('status', 'success')->where('is_confirmed', false)->count(),
            'confirmed' => Payment::where('is_confirmed', true)->count(),
            'failed' => Payment::where('status', 'failed')->count(),
        ];

        return view('admin.payment-confirmation.index', compact('payments', 'competitions', 'stats'));
    }

    /**
     * Show payment details
     */
    public function show(Payment $payment)
    {
        $payment->load(['registration.user', 'registration.competition']);
        return view('admin.payment-confirmation.show', compact('payment'));
    }

    /**
     * Confirm payment
     */
    public function confirm(Request $request, Payment $payment)
    {
        $request->validate([
            'confirmation_notes' => 'nullable|string|max:500'
        ]);

        try {
            $payment->update([
                'is_confirmed' => true,
                'confirmed_at' => now(),
                'confirmed_by' => Auth::id(),
                'confirmation_notes' => $request->confirmation_notes,
            ]);

            // Update registration status to confirmed
            $payment->registration->update([
                'status' => 'confirmed'
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil dikonfirmasi.'
                ]);
            }

            return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Reject payment confirmation
     */
    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $payment->update([
                'is_confirmed' => false,
                'confirmed_at' => null,
                'confirmed_by' => null,
                'confirmation_notes' => $request->rejection_reason,
                'status' => 'failed',
            ]);

            // Update registration status back to pending
            $payment->registration->update([
                'status' => 'pending'
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil ditolak.'
                ]);
            }

            return back()->with('success', 'Pembayaran berhasil ditolak.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menolak pembayaran: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Bulk confirm payments
     */
    public function bulkConfirm(Request $request)
    {
        $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id',
            'bulk_notes' => 'nullable|string|max:500'
        ]);

        try {
            $payments = Payment::whereIn('id', $request->payment_ids)
                ->where('status', 'success')
                ->where('is_confirmed', false)
                ->get();
            
            foreach ($payments as $payment) {
                $payment->update([
                    'is_confirmed' => true,
                    'confirmed_at' => now(),
                    'confirmed_by' => Auth::id(),
                    'confirmation_notes' => $request->bulk_notes,
                ]);

                // Update registration status
                $payment->registration->update([
                    'status' => 'confirmed'
                ]);
            }

            return back()->with('success', 'Berhasil mengkonfirmasi ' . $payments->count() . ' pembayaran.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan konfirmasi massal: ' . $e->getMessage());
        }
    }
}
