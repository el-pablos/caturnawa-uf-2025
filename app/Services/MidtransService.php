<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

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
        // Set your Merchant Server Key
        Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('midtrans.is_production', false);
        // Set sanitization on (default)
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        // Set 3DS transaction for credit card to true
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    /**
     * Buat transaksi pembayaran baru
     *
     * @param \App\Models\Registration $registration
     * @param string|null $paymentMethod
     * @return array
     */
    public function createTransaction(Registration $registration, $paymentMethod = null)
    {
        // Buat atau update payment record
        $payment = Payment::firstOrCreate(
            ['registration_id' => $registration->id],
            [
                'gross_amount' => $registration->amount,
                'transaction_status' => 'pending',
                'expired_at' => now()->addHours(24), // 24 jam untuk pembayaran
            ]
        );

        // Siapkan parameter untuk Midtrans
        $params = $this->buildTransactionParams($registration, $payment, $paymentMethod);

        try {
            // Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);
            
            // Update payment record dengan snap token
            $payment->update(['snap_token' => $snapToken]);

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'payment_id' => $payment->id,
                'redirect_url' => $this->getRedirectUrl($payment),
            ];
        } catch (\Exception $e) {
            \Log::error('Midtrans Transaction Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Gagal membuat transaksi pembayaran: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Bangun parameter transaksi untuk Midtrans
     *
     * @param \App\Models\Registration $registration
     * @param \App\Models\Payment $payment
     * @param string|null $paymentMethod
     * @return array
     */
    protected function buildTransactionParams(Registration $registration, Payment $payment, $paymentMethod = null)
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

        // Fill transaction details
        $transaction = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        ];

        // Optional: Add enabled payment methods
        $enabledPayments = config('midtrans.enabled_payments');

        // If specific payment method is selected, filter enabled payments
        if ($paymentMethod) {
            $methodMapping = [
                'credit_card' => ['credit_card'],
                'bank_transfer' => ['bca_va', 'bni_va', 'bri_va', 'echannel', 'permata_va', 'other_va'],
                'ewallet' => ['gopay', 'shopeepay'],
                'qris' => ['other_qris'],
                'convenience_store' => ['indomaret', 'alfamart']
            ];

            if (isset($methodMapping[$paymentMethod])) {
                $transaction['enabled_payments'] = $methodMapping[$paymentMethod];
            }
        } elseif (!empty($enabledPayments)) {
            $transaction['enabled_payments'] = $enabledPayments;
        }

        // Optional: Add custom expiry
        $transaction['custom_expiry'] = [
            'order_time' => now()->format('Y-m-d H:i:s O'),
            'expiry_duration' => config('midtrans.custom_expiry.duration', 24),
            'unit' => config('midtrans.custom_expiry.unit', 'hour')
        ];

        return $transaction;
    }

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
     * @param array $notification
     * @return void
     */
    protected function processTransactionStatus(Payment $payment, $notification)
    {
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
     * 
     * @param \App\Models\Payment $payment
     * @return void
     */
    protected function processSuccessfulPayment(Payment $payment)
    {
        // Konfirmasi pendaftaran
        $registration = $payment->registration;
        if (!$registration->isConfirmed()) {
            $registration->confirm();
        }

        // Log event
        $this->logTransactionEvent($payment, 'Payment successful, registration confirmed');
        
        // TODO: Kirim email konfirmasi dan e-ticket
        // $this->sendConfirmationEmail($registration);
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
