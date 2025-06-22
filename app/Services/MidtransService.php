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
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Buat transaksi pembayaran baru
     * 
     * @param \App\Models\Registration $registration
     * @return array
     */
    public function createTransaction(Registration $registration)
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
        $params = $this->buildTransactionParams($registration, $payment);

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
     * @return array
     */
    protected function buildTransactionParams(Registration $registration, Payment $payment)
    {
        $user = $registration->user;
        $competition = $registration->competition;

        return [
            'transaction_details' => [
                'order_id' => $payment->order_id,
                'gross_amount' => intval($registration->amount),
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $registration->phone ?: $user->phone,
            ],
            'item_details' => [
                [
                    'id' => $competition->id,
                    'price' => intval($registration->amount),
                    'quantity' => 1,
                    'name' => "Pendaftaran {$competition->name}",
                    'category' => $competition->category,
                ]
            ],
            'callbacks' => [
                'finish' => route('payment.finish', $payment->id),
                'unfinish' => route('payment.unfinish', $payment->id),
                'error' => route('payment.error', $payment->id),
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24,
            ],
            'custom_field1' => $registration->registration_number,
            'custom_field2' => $competition->slug,
            'custom_field3' => config('app.name'),
        ];
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
