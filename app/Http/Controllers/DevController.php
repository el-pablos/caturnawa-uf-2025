<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\User;

class DevController extends Controller
{
    public function __construct()
    {
        // Only allow in development environment
        if (!app()->environment(['local', 'development'])) {
            abort(404);
        }
        
        $this->middleware('auth');
    }

    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_registrations' => Registration::count(),
            'total_payments' => Payment::count(),
            'pending_registrations' => Registration::where('status', 'pending')->count(),
            'confirmed_registrations' => Registration::where('status', 'confirmed')->count(),
            'paid_payments' => Payment::where('transaction_status', 'settlement')->count(),
        ];

        $recent_registrations = Registration::with(['user', 'competition', 'payments'])
            ->latest()
            ->limit(10)
            ->get();

        $recent_payments = Payment::with(['registration.user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dev.index', compact('stats', 'recent_registrations', 'recent_payments'));
    }

    public function resetPayments(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'registration_id' => 'nullable|exists:registrations,id',
            'reset_all' => 'nullable|boolean',
        ]);

        $query = Registration::query();
        $scope = '';

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
            $user = User::find($request->user_id);
            $scope = "user: {$user->name}";
        } elseif ($request->registration_id) {
            $query->where('id', $request->registration_id);
            $scope = "registration ID: {$request->registration_id}";
        } elseif ($request->reset_all) {
            $scope = "ALL registrations";
        } else {
            return back()->with('error', 'Please specify what to reset');
        }

        $registrations = $query->with('payments')->get();

        if ($registrations->isEmpty()) {
            return back()->with('info', 'No registrations found to reset.');
        }

        $totalPayments = 0;
        $totalRegistrations = 0;

        foreach ($registrations as $registration) {
            $paymentCount = $registration->payments->count();
            $totalPayments += $paymentCount;

            // Delete payments
            $registration->payments()->delete();

            // Reset registration status
            $registration->update([
                'status' => 'pending',
                'qr_code' => null,
                'confirmed_at' => null,
            ]);

            $totalRegistrations++;
        }

        return back()->with('success', 
            "Successfully reset {$totalRegistrations} registrations and deleted {$totalPayments} payments for {$scope}."
        );
    }

    public function regenerateQR(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'registration_id' => 'nullable|exists:registrations,id',
        ]);

        $query = Registration::where('status', 'confirmed');

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        } elseif ($request->registration_id) {
            $query->where('id', $request->registration_id);
        }

        $registrations = $query->get();

        if ($registrations->isEmpty()) {
            return back()->with('info', 'No confirmed registrations found.');
        }

        $success = 0;
        $failed = 0;

        foreach ($registrations as $registration) {
            try {
                $registration->generateQRCode();
                $success++;
            } catch (\Exception $e) {
                $failed++;
                \Log::error("Failed to generate QR for registration #{$registration->registration_number}: " . $e->getMessage());
            }
        }

        $message = "QR regeneration completed: {$success} success";
        if ($failed > 0) {
            $message .= ", {$failed} failed";
        }

        return back()->with('success', $message);
    }

    public function testPayment(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'status' => 'required|in:pending,settlement,cancel,expire,failure',
        ]);

        $registration = Registration::find($request->registration_id);
        
        // Create or update payment
        $payment = $registration->payments()->first();
        
        if (!$payment) {
            $payment = Payment::create([
                'registration_id' => $registration->id,
                'order_id' => 'TEST-' . time(),
                'gross_amount' => $registration->amount,
                'transaction_status' => $request->status,
                'payment_method' => 'test',
            ]);
        } else {
            $payment->update([
                'transaction_status' => $request->status,
            ]);
        }

        // Update registration status based on payment
        if ($request->status === 'settlement') {
            $registration->update([
                'status' => 'paid',
            ]);
        }

        return back()->with('success', 
            "Test payment created/updated for registration #{$registration->registration_number} with status: {$request->status}"
        );
    }
}
