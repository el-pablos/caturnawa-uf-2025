<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use App\Helpers\MidtransHelper;

/**
 * Service untuk integrasi Midtrans Payment Gateway
 * 
 * Menangani pembuatan transaksi, notifikasi, dan update status pembayaran
 */
class MidtransService
{
    /**
     * Constructor - Setup konfigurasi Midtrans
     */
    public function __construct()
    {
        // Use helper to initialize Midtrans config
        MidtransHelper::initMidtransConfig();
    }

    /**
     * Check if Midtrans is properly configured
     */
    public function isConfigured(): bool
    {
        $serverKey = config('midtrans.server_key', '');
        $clientKey = config('midtrans.client_key', '');

        return !empty($serverKey) && !empty($clientKey);
    }

    /**
     * Buat transaksi pembayaran baru dengan auto-detect payment methods
     *
     * @param \App\Models\Registration $registration
     * @return array
     */
    public function createTransaction(Registration $registration)
    {
        // Check if Midtrans is properly configured
        if (!$this->isConfigured()) {
            Log::error('Midtrans not configured - cannot create transaction');
            return [
                'success' => false,
                'message' => 'Payment gateway tidak dikonfigurasi. Silakan hubungi administrator.',
            ];
        }

        // Check if there's already a successful payment for this registration
        $existingPayment = Payment::where('registration_id', $registration->id)
            ->whereIn('transaction_status', ['settlement', 'capture'])
            ->first();

        if ($existingPayment) {
            return [
                'success' => false,
                'message' => 'Pembayaran untuk pendaftaran ini sudah berhasil.',
            ];
        }

        // Check for existing pending payment that's still valid
        $existingPayment = Payment::where('registration_id', $registration->id)
            ->whereNotIn('transaction_status', ['settlement', 'capture'])
            ->where('expired_at', '>', now())
            ->first();

        if ($existingPayment && $existingPayment->snap_token) {
            // Return existing payment if still valid
            Log::info('Reusing existing payment', [
                'payment_id' => $existingPayment->id,
                'order_id' => $existingPayment->order_id,
                'expires_at' => $existingPayment->expired_at
            ]);

            return [
                'success' => true,
                'snap_token' => $existingPayment->snap_token,
                'payment_id' => $existingPayment->id,
                'order_id' => $existingPayment->order_id,
                'redirect_url' => $this->getRedirectUrl($existingPayment),
            ];
        }

        // Delete only expired pending payments
        Payment::where('registration_id', $registration->id)
            ->whereNotIn('transaction_status', ['settlement', 'capture'])
            ->where('expired_at', '<=', now())
            ->delete();

        // Create new payment record
        $payment = Payment::create([
            'registration_id' => $registration->id,
            'gross_amount' => $registration->amount,
            'transaction_status' => 'pending',
            'expired_at' => now()->addHours(24), // 24 jam untuk pembayaran
        ]);

        // Siapkan parameter untuk Midtrans dengan auto-detect payment methods
        $params = $this->buildTransactionParams($registration, $payment);

        try {
            // Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Update payment record dengan snap token
            $payment->update(['snap_token' => $snapToken]);

            Log::info('Transaction created successfully', [
                'order_id' => $payment->order_id,
                'registration_id' => $registration->id,
                'amount' => $registration->amount,
                'snap_token' => substr($snapToken, 0, 20) . '...',
                'auto_detect' => true
            ]);

            // Wait a moment for Midtrans to process the transaction
            sleep(1);

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'redirect_url' => $this->getRedirectUrl($payment),
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Transaction Error: ' . $e->getMessage(), [
                'order_id' => $payment->order_id,
                'registration_id' => $registration->id,
                'auto_detect' => true
            ]);

            return [
                'success' => false,
                'message' => 'Gagal membuat transaksi pembayaran: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Bangun parameter transaksi untuk Midtrans dengan auto-detect payment methods
     *
     * @param \App\Models\Registration $registration
     * @param \App\Models\Payment $payment
     * @return array
     */
    protected function buildTransactionParams(Registration $registration, Payment $payment)
    {
        $user = $registration->user;
        $competition = $registration->competition;

        // Required
        $transaction_details = [
            'order_id' => $payment->order_id,
            'gross_amount' => intval($registration->amount), // no decimal allowed for creditcard
        ];

        // Optional
        $item_details = [
            [
                'id' => 'comp_' . $competition->id,
                'price' => intval($registration->amount),
                'quantity' => 1,
                'name' => "Pendaftaran " . $competition->name,
                'brand' => config('app.name'),
                'category' => $competition->category,
                'merchant_name' => config('app.name')
            ]
        ];

        // Optional
        $customer_details = [
            'first_name'    => $user->name,
            'last_name'     => '',
            'email'         => $user->email,
            'phone'         => $registration->phone ?: $user->phone,
        ];

        // Simplified transaction structure matching working examples
        $time = time();
        $custom_expiry = [
            'start_time' => date("Y-m-d H:i:s O", $time),
            'unit' => 'hour',
            'duration' => 24
        ];

        $transaction = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details,
            'expiry' => $custom_expiry,
            'callbacks' => [
                'finish' => route('payment.finish', ['payment' => $payment->id]),
                'unfinish' => route('payment.status', ['paymentId' => $payment->id]),
                'error' => route('payment.error', ['payment' => $payment->id])
            ]
        ];

        return $transaction;
    }

    /**
     * Configure payment method specific parameters
     *
     * @param array $transaction
     * @param string $paymentMethod
     * @return array
     */
    protected function configurePaymentMethod($transaction, $paymentMethod)
    {
        switch (strtolower($paymentMethod)) {
            case 'qris':
                // QRIS specific configuration - Use standard QRIS without specific acquirer
                $transaction['enabled_payments'] = ['qris'];
                // Remove acquirer specification to allow Midtrans to handle it properly
                unset($transaction['qris']);
                // Add additional configuration for QRIS stability
                $transaction['custom_field1'] = 'qris_payment';
                $transaction['custom_field2'] = 'unas_fest_2025';
                break;

            case 'gopay':
                $transaction['enabled_payments'] = ['gopay'];
                $transaction['gopay'] = [
                    'enable_callback' => true,
                    'callback_url' => route('payment.notification')
                ];
                break;

            case 'shopeepay':
                $transaction['enabled_payments'] = ['shopeepay'];
                $transaction['shopeepay'] = [
                    'callback_url' => route('payment.notification')
                ];
                break;

            case 'bank_transfer':
                $transaction['enabled_payments'] = ['bank_transfer'];
                $transaction['bank_transfer'] = [
                    'bank' => 'permata'
                ];
                break;

            case 'credit_card':
                $transaction['enabled_payments'] = ['credit_card'];
                $transaction['credit_card'] = [
                    'secure' => true,
                    'channel' => 'migs',
                    'bank' => 'bca'
                ];
                break;

            default:
                // Keep all payment methods enabled if no specific method selected
                break;
        }

        return $transaction;
    }

    // QRIS method removed - using auto-detect payment methods now

    /**
     * Handle notifikasi dari Midtrans
     *
     * @param array $notification
     * @return array
     */
    public function handleNotification($notification = null)
    {
        try {
            // Jika tidak ada notifikasi yang diberikan, ambil dari POST
            if (!$notification) {
                $notification = new Notification();
                $notification = $notification->getResponse();
            }

            // Convert object to array if needed
            if (is_object($notification)) {
                $notification = json_decode(json_encode($notification), true);
            }

            // Cari payment berdasarkan order_id
            $payment = Payment::where('order_id', $notification['order_id'])->first();

            if (!$payment) {
                return [
                    'success' => false,
                    'message' => 'Payment not found',
                ];
            }

            // Update payment dengan data dari notifikasi
            $payment->updateFromMidtrans($notification);

            // Proses berdasarkan status transaksi
            $this->processTransactionStatus($payment, $notification);

            return [
                'success' => true,
                'message' => 'Notification processed successfully',
                'payment_id' => $payment->id,
                'status' => $notification['transaction_status'],
            ];

        } catch (\Exception $e) {
            \Log::error('Midtrans Notification Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error processing notification: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Proses status transaksi dari notifikasi
     *
     * @param \App\Models\Payment $payment
     * @param array|object $notification
     * @return void
     */
    protected function processTransactionStatus(Payment $payment, $notification)
    {
        // Convert object to array if needed
        if (is_object($notification)) {
            $notification = json_decode(json_encode($notification), true);
        }

        $transactionStatus = $notification['transaction_status'];
        $fraudStatus = $notification['fraud_status'] ?? null;

        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus == 'challenge') {
                    // Transaksi di-challenge, perlu review manual
                    $this->logTransactionEvent($payment, 'Transaction challenged, requires manual review');
                } elseif ($fraudStatus == 'accept') {
                    // Transaksi berhasil
                    $this->processSuccessfulPayment($payment);
                }
                break;

            case 'settlement':
                // Transaksi berhasil (untuk bank transfer, e-wallet, dll)
                $this->processSuccessfulPayment($payment);
                break;

            case 'pending':
                // Transaksi pending, menunggu pembayaran dari customer
                $this->logTransactionEvent($payment, 'Transaction pending, waiting for customer payment');
                break;

            case 'deny':
                // Transaksi ditolak
                $this->processFailedPayment($payment, 'Transaction denied');
                break;

            case 'cancel':
                // Transaksi dibatalkan customer
                $this->processFailedPayment($payment, 'Transaction cancelled by customer');
                break;

            case 'expire':
                // Transaksi expired
                $this->processExpiredPayment($payment);
                break;

            case 'failure':
                // Transaksi gagal
                $this->processFailedPayment($payment, 'Transaction failed');
                break;

            default:
                $this->logTransactionEvent($payment, "Unknown transaction status: {$transactionStatus}");
                break;
        }
    }

    /**
     * Proses pembayaran berhasil
     * MODIFIED: Auto-confirm registration and payment without admin intervention
     *
     * @param \App\Models\Payment $payment
     * @return void
     */
    protected function processSuccessfulPayment(Payment $payment)
    {
        $registration = $payment->registration;

        // Auto-confirm registration after successful payment (MODIFIED: No admin confirmation needed)
        if (in_array($registration->status, ['pending', 'paid'])) {
            $registration->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'confirmed_by' => null, // System auto-confirmation
            ]);

            // Auto-confirm payment as well (MODIFIED: No admin confirmation needed)
            $payment->update([
                'is_confirmed' => true,
                'confirmed_at' => now(),
                'confirmed_by' => null, // System auto-confirmation
                'confirmation_notes' => 'Pembayaran dikonfirmasi otomatis oleh sistem setelah pembayaran berhasil'
            ]);

            // Generate QR Code for confirmed registration
            $registration->generateQRCode();
        }

        // Log event
        $this->logTransactionEvent($payment, 'Payment successful, registration and payment auto-confirmed by system');

        // TODO: Kirim email konfirmasi pembayaran dan registrasi ke peserta
        // $this->sendPaymentConfirmationEmail($registration);
    }

    /**
     * Proses pembayaran gagal
     * 
     * @param \App\Models\Payment $payment
     * @param string $reason
     * @return void
     */
    protected function processFailedPayment(Payment $payment, $reason)
    {
        // Log event
        $this->logTransactionEvent($payment, "Payment failed: {$reason}");
        
        // TODO: Kirim notifikasi ke customer
        // $this->sendFailureNotification($payment->registration, $reason);
    }

    /**
     * Proses pembayaran expired
     * 
     * @param \App\Models\Payment $payment
     * @return void
     */
    protected function processExpiredPayment(Payment $payment)
    {
        // Expire registration jika belum dikonfirmasi
        $registration = $payment->registration;
        if (!$registration->isConfirmed()) {
            $registration->expire();
        }

        // Log event
        $this->logTransactionEvent($payment, 'Payment expired, registration expired');
        
        // TODO: Kirim notifikasi expired ke customer
        // $this->sendExpiryNotification($registration);
    }

    /**
     * Log event transaksi
     * 
     * @param \App\Models\Payment $payment
     * @param string $message
     * @return void
     */
    protected function logTransactionEvent(Payment $payment, $message)
    {
        \Log::info("Payment ID {$payment->id} - Order ID {$payment->order_id}: {$message}");
    }

    /**
     * Dapatkan URL redirect setelah pembayaran
     * 
     * @param \App\Models\Payment $payment
     * @return string
     */
    protected function getRedirectUrl(Payment $payment)
    {
        return route('payment.status', $payment->id);
    }

    /**
     * Cek status transaksi dari Midtrans
     * 
     * @param string $orderId
     * @return array
     */
    public function checkTransactionStatus($orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);
            
            return [
                'success' => true,
                'data' => $status,
            ];
        } catch (\Exception $e) {
            \Log::error('Midtrans Status Check Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error checking transaction status: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Cancel transaksi
     * 
     * @param string $orderId
     * @return array
     */
    public function cancelTransaction($orderId)
    {
        try {
            $cancel = \Midtrans\Transaction::cancel($orderId);
            
            return [
                'success' => true,
                'data' => $cancel,
            ];
        } catch (\Exception $e) {
            \Log::error('Midtrans Cancel Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error cancelling transaction: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Refund transaksi
     * 
     * @param string $orderId
     * @param int $amount
     * @param string $reason
     * @return array
     */
    public function refundTransaction($orderId, $amount = null, $reason = 'Customer request')
    {
        try {
            $params = [
                'refund_key' => $orderId . '-refund-' . time(),
                'amount' => $amount,
                'reason' => $reason,
            ];

            $refund = \Midtrans\Transaction::refund($orderId, $params);
            
            return [
                'success' => true,
                'data' => $refund,
            ];
        } catch (\Exception $e) {
            \Log::error('Midtrans Refund Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error processing refund: ' . $e->getMessage(),
            ];
        }
    }
}
