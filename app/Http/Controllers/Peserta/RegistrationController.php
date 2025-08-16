<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $registrations = Registration::with(['competition', 'payment'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peserta.registrations.index', compact('registrations'));
    }

    public function show(Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to registration');
        }

        $registration->load(['competition', 'payment', 'teamMembers']);

        // Fix missing payment amount data if needed
        if ($registration->payment) {
            $payment = $registration->payment;
            if ($payment->amount == 0 && $payment->gross_amount > 0) {
                $payment->update(['amount' => $payment->gross_amount]);
                Log::info('Auto-fixed missing amount data for payment on page load', [
                    'payment_id' => $payment->id,
                    'amount' => $payment->gross_amount
                ]);
            } elseif ($payment->amount == 0 && $registration->amount > 0) {
                $payment->update([
                    'amount' => $registration->amount,
                    'gross_amount' => $registration->amount
                ]);
                Log::info('Auto-fixed missing amount data from registration on page load', [
                    'payment_id' => $payment->id,
                    'amount' => $registration->amount
                ]);
            }
        }

        return view('peserta.registrations.show', compact('registration'));
    }

    /**
     * Show documents upload page
     */
    public function documents(Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to registration');
        }

        return view('peserta.registrations.documents', compact('registration'));
    }

    public function update(Request $request, Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to registration');
        }

        // Only allow updates for pending registrations
        if ($registration->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot update confirmed or cancelled registration');
        }

        $request->validate([
            'team_name' => 'nullable|string|max:255',
            'team_members' => 'nullable|array',
            'team_members.*.name' => 'required|string|max:255',
            'team_members.*.email' => 'required|email|max:255',
            'team_members.*.phone' => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        
        try {
            // Update registration
            $registration->update([
                'team_name' => $request->team_name,
                'updated_at' => now(),
            ]);

            // Update team members if provided
            if ($request->has('team_members') && $registration->competition->is_team) {
                // Remove existing team members
                $registration->teamMembers()->delete();
                
                // Add new team members
                foreach ($request->team_members as $member) {
                    $registration->teamMembers()->create([
                        'name' => $member['name'],
                        'email' => $member['email'],
                        'phone' => $member['phone'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('peserta.registrations.show', $registration)
                ->with('success', 'Registration updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update registration: ' . $e->getMessage());
        }
    }

    public function cancel(Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to registration');
        }

        // Only allow cancellation for pending registrations
        if ($registration->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot cancel confirmed registration');
        }

        DB::beginTransaction();
        
        try {
            $registration->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // If there's a pending payment, cancel it too
            if ($registration->payment && $registration->payment->status === 'pending') {
                $registration->payment->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('peserta.registrations.index')
                ->with('success', 'Registration cancelled successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to cancel registration: ' . $e->getMessage());
        }
    }

    public function ticket(Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to registration');
        }

        // Only confirmed registrations have tickets
        if ($registration->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Ticket is only available for confirmed registrations');
        }

        return view('peserta.registrations.ticket', compact('registration'));
    }

    /**
     * Refresh payment status from Midtrans
     */
    public function refreshPaymentStatus(Registration $registration, MidtransService $midtransService)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to registration');
        }

        // Check if registration has payment
        if (!$registration->payment) {
            return redirect()->back()->with('error', 'Tidak ada data pembayaran untuk registrasi ini.');
        }

        try {
            $payment = $registration->payment;

            // Fix missing amount data if needed
            if ($payment->amount == 0 && $payment->gross_amount > 0) {
                $payment->update(['amount' => $payment->gross_amount]);
                Log::info('Fixed missing amount data for payment', [
                    'payment_id' => $payment->id,
                    'amount' => $payment->gross_amount
                ]);
            } elseif ($payment->amount == 0 && $registration->amount > 0) {
                $payment->update([
                    'amount' => $registration->amount,
                    'gross_amount' => $registration->amount
                ]);
                Log::info('Fixed missing amount data from registration', [
                    'payment_id' => $payment->id,
                    'amount' => $registration->amount
                ]);
            }

            // Check if payment has order_id
            if (!$payment->order_id) {
                return redirect()->back()->with('error', 'Order ID tidak ditemukan. Tidak dapat mengecek status pembayaran.');
            }

            Log::info('Manual payment status refresh requested', [
                'registration_id' => $registration->id,
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'current_status' => $payment->transaction_status
            ]);

            // Check transaction status from Midtrans
            $result = $midtransService->checkTransactionStatus($payment->order_id);

            if ($result['success']) {
                $data = $result['data'];
                if (is_object($data)) {
                    $data = json_decode(json_encode($data), true);
                }

                Log::info('Midtrans status check result for manual refresh', [
                    'payment_id' => $payment->id,
                    'midtrans_status' => $data['transaction_status'] ?? 'unknown',
                    'current_db_status' => $payment->transaction_status
                ]);

                // Update payment from Midtrans response
                $payment->updateFromMidtrans($data);

                return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui dari Midtrans.');
            } else {
                Log::warning('Failed to check payment status from Midtrans', [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'error' => $result['message'] ?? 'Unknown error'
                ]);

                return redirect()->back()->with('error', 'Gagal mengecek status pembayaran: ' . ($result['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error('Error refreshing payment status', [
                'registration_id' => $registration->id,
                'payment_id' => $registration->payment->id ?? null,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui status pembayaran: ' . $e->getMessage());
        }
    }
}
