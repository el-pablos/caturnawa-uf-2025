<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Payment::with(['registration.competition'])
            ->whereHas('registration', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                    'registration' => [
                        'id' => $payment->registration->id,
                        'registration_number' => $payment->registration->registration_number,
                        'competition_name' => $payment->registration->competition->name,
                    ],
                    'amount' => $payment->amount,
                    'status' => $payment->status,
                    'payment_method' => $payment->payment_method,
                    'paid_at' => $payment->paid_at?->toISOString(),
                    'created_at' => $payment->created_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    public function datatable(Request $request)
    {
        $user = Auth::user();
        
        $query = Payment::with(['registration.competition'])
            ->whereHas('registration', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                  ->orWhereHas('registration', function($q) use ($search) {
                      $q->where('registration_number', 'like', "%{$search}%")
                        ->orWhereHas('competition', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                  });
            });
        }

        // Total records
        $totalRecords = $query->count();

        // Order
        if ($request->has('order')) {
            $orderColumn = $request->columns[$request->order[0]['column']]['data'] ?? 'created_at';
            $orderDirection = $request->order[0]['dir'] ?? 'desc';
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        if ($request->has('start') && $request->has('length')) {
            $query->offset($request->start)->limit($request->length);
        }

        $payments = $query->get()->map(function($payment) {
            return [
                'id' => $payment->id,
                'payment_code' => $payment->payment_code,
                'registration_number' => $payment->registration->registration_number,
                'competition_name' => $payment->registration->competition->name,
                'amount' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                'status' => $payment->status,
                'payment_method' => $payment->payment_method ?? '-',
                'paid_at' => $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-',
                'created_at' => $payment->created_at->format('d/m/Y H:i'),
                'actions' => view('peserta.payments.actions', compact('payment'))->render(),
            ];
        });

        return response()->json([
            'draw' => (int) $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $payments
        ]);
    }
}
