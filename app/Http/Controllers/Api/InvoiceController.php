<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Get invoice data for Finance Department
     *
     * @param string $paymentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($paymentId)
    {
        // Check if user has finance role or admin access
        $user = Auth::user();
        if (!$user || (!$user->hasRole('admin') && !$user->hasRole('finance'))) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Only finance department can access invoice data.'
            ], 403);
        }

        // Find payment by ID or order_id
        $payment = Payment::where('id', $paymentId)
            ->orWhere('order_id', $paymentId)
            ->with(['registration.user', 'registration.competition'])
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        // Calculate due date (typically 24 hours from creation for competition payments)
        $dueDate = $payment->created_at->addHours(24);

        // Prepare invoice data
        $invoiceData = [
            'invoice_number' => $this->generateInvoiceNumber($payment),
            'payment_id' => $payment->id,
            'registration_id' => $payment->registration->id,
            'order_id' => $payment->order_id,
            'status' => $payment->status,
            'amount' => [
                'subtotal' => $payment->amount,
                'tax' => 0, // No tax for competition fees
                'discount' => 0,
                'total' => $payment->amount,
                'currency' => 'IDR',
                'formatted_total' => 'Rp ' . number_format($payment->amount, 0, ',', '.')
            ],
            'dates' => [
                'created_at' => $payment->created_at->toISOString(),
                'due_date' => $dueDate->toISOString(),
                'paid_at' => $payment->paid_at?->toISOString(),
                'expired_at' => $payment->expired_at?->toISOString(),
            ],
            'user' => [
                'id' => $payment->registration->user->id,
                'name' => $payment->registration->user->name,
                'email' => $payment->registration->user->email,
                'phone' => $payment->registration->user->phone,
                'institution' => $payment->registration->user->institution,
                'registration_number' => $payment->registration->registration_number,
            ],
            'items' => [
                [
                    'description' => 'Biaya Pendaftaran - ' . $payment->registration->competition->name,
                    'competition_name' => $payment->registration->competition->name,
                    'competition_id' => $payment->registration->competition->id,
                    'category' => 'Registration Fee',
                    'quantity' => 1,
                    'unit_price' => $payment->amount,
                    'total_price' => $payment->amount,
                    'formatted_price' => 'Rp ' . number_format($payment->amount, 0, ',', '.')
                ]
            ],
            'payment_details' => [
                'method' => $payment->payment_method ?? 'Bank Transfer',
                'channel' => $payment->payment_channel ?? 'Midtrans',
                'reference' => $payment->transaction_id,
                'bank' => $payment->bank ?? null,
                'va_number' => $payment->va_number ?? null,
            ],
            'finance_notes' => [
                'department' => 'Finance Department - UNAS Fest 2025',
                'processed_by' => $user->name,
                'processed_at' => now()->toISOString(),
                'verification_status' => $this->getVerificationStatus($payment),
                'accounting_code' => $this->getAccountingCode($payment->registration->competition),
            ],
            'metadata' => [
                'generated_for' => 'Finance Department',
                'api_version' => '1.0',
                'export_format' => 'JSON',
                'timestamp' => now()->toISOString(),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $invoiceData
        ]);
    }

    /**
     * Get all invoices for Finance Department with filtering
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || (!$user->hasRole('admin') && !$user->hasRole('finance'))) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $query = Payment::with(['registration.user', 'registration.competition']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('competition_id')) {
            $query->whereHas('registration', function($q) use ($request) {
                $q->where('competition_id', $request->competition_id);
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }

        if ($request->has('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        // Pagination
        $perPage = $request->get('per_page', 20);
        $payments = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Transform data for finance
        $invoices = $payments->getCollection()->map(function($payment) {
            return [
                'payment_id' => $payment->id,
                'invoice_number' => $this->generateInvoiceNumber($payment),
                'order_id' => $payment->order_id,
                'user_name' => $payment->registration->user->name,
                'user_email' => $payment->registration->user->email,
                'competition_name' => $payment->registration->competition->name,
                'amount' => $payment->amount,
                'formatted_amount' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                'status' => $payment->status,
                'created_at' => $payment->created_at->toISOString(),
                'paid_at' => $payment->paid_at?->toISOString(),
                'due_date' => $payment->created_at->addHours(24)->toISOString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'invoices' => $invoices,
                'pagination' => [
                    'current_page' => $payments->currentPage(),
                    'last_page' => $payments->lastPage(),
                    'per_page' => $payments->perPage(),
                    'total' => $payments->total(),
                    'from' => $payments->firstItem(),
                    'to' => $payments->lastItem(),
                ],
                'summary' => [
                    'total_amount' => $payments->getCollection()->sum('amount'),
                    'paid_amount' => $payments->getCollection()->where('status', 'paid')->sum('amount'),
                    'pending_amount' => $payments->getCollection()->where('status', 'pending')->sum('amount'),
                    'total_invoices' => $payments->total(),
                ]
            ]
        ]);
    }

    /**
     * Generate invoice number
     *
     * @param Payment $payment
     * @return string
     */
    private function generateInvoiceNumber(Payment $payment)
    {
        $date = $payment->created_at->format('Ymd');
        $competitionCode = strtoupper(substr($payment->registration->competition->slug, 0, 3));
        return "INV-{$competitionCode}-{$date}-{$payment->id}";
    }

    /**
     * Get verification status
     *
     * @param Payment $payment
     * @return string
     */
    private function getVerificationStatus(Payment $payment)
    {
        switch ($payment->status) {
            case 'paid':
                return 'verified';
            case 'pending':
                return 'pending_verification';
            case 'failed':
                return 'failed_verification';
            case 'expired':
                return 'expired';
            default:
                return 'unknown';
        }
    }

    /**
     * Get accounting code for competition
     *
     * @param \App\Models\Competition $competition
     * @return string
     */
    private function getAccountingCode($competition)
    {
        // Generate accounting code based on competition type
        $baseCode = '4100'; // Revenue account
        $competitionCode = str_pad($competition->id, 3, '0', STR_PAD_LEFT);
        return "{$baseCode}.{$competitionCode}";
    }
}
