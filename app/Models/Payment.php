<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Model Payment untuk mengelola data pembayaran
 * 
 * Kelas ini menangani integrasi dengan Midtrans
 * dan tracking status pembayaran peserta
 */
class Payment extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_id',
        'order_id',
        'gross_amount',
        'amount',
        'payment_type',
        'payment_method',
        'bank',
        'va_number',
        'transaction_status',
        'status',
        'transaction_id',
        'fraud_status',
        'status_code',
        'status_message',
        'payment_code',
        'pdf_url',
        'finish_redirect_url',
        'snap_token',
        'paid_at',
        'expired_at',
        'midtrans_response',
        'is_confirmed',
        'confirmed_at',
        'confirmed_by',
        'confirmation_notes',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'midtrans_response' => 'array',
        'gross_amount' => 'decimal:2',
        'amount' => 'decimal:2',
        'is_confirmed' => 'boolean',
        'confirmed_at' => 'datetime',
    ];

    /**
     * Konstanta untuk status pembayaran
     */
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    /**
     * Boot method untuk generate order ID otomatis
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payment) {
            if (!$payment->order_id) {
                $payment->order_id = $payment->generateOrderId();
            }
        });
    }

    /**
     * Relasi dengan model Registration
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Generate Order ID unik untuk Midtrans
     *
     * @return string
     */
    protected function generateOrderId()
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $attempts++;
            $timestamp = now()->format('YmdHis');
            $microseconds = substr(microtime(), 2, 6); // Get microseconds for more uniqueness
            $random = str_pad(mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);
            $orderId = "UF2025-{$timestamp}{$microseconds}-{$random}";

            // Check for collision in database with lock to prevent race conditions
            $exists = DB::table('payments')
                ->where('order_id', $orderId)
                ->lockForUpdate()
                ->exists();

            if (!$exists) {
                return $orderId;
            }

            // Add small delay to prevent rapid collision attempts
            usleep(1000); // 1ms delay

        } while ($exists && $attempts < $maxAttempts);

        // If we still have collision after max attempts, throw exception
        if ($attempts >= $maxAttempts) {
            throw new \Exception('Unable to generate unique order ID after ' . $maxAttempts . ' attempts');
        }

        return $orderId;
    }

    /**
     * Scope untuk pembayaran yang berhasil
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuccess($query)
    {
        return $query->where('transaction_status', 'settlement')
                    ->orWhere('transaction_status', 'capture');
    }

    /**
     * Scope untuk pembayaran yang pending
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('transaction_status', 'pending');
    }

    /**
     * Scope untuk pembayaran yang gagal
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('transaction_status', ['deny', 'cancel', 'expire', 'failure']);
    }

    /**
     * Update status pembayaran dari notifikasi Midtrans
     *
     * @param array|object $notification
     * @return void
     */
    public function updateFromMidtrans($notification)
    {
        // Convert object to array if needed
        if (is_object($notification)) {
            $notification = json_decode(json_encode($notification), true);
        }

        // Use database locking to prevent race conditions
        return DB::transaction(function() use ($notification) {
            // Lock the payment record for update
            $payment = Payment::where('id', $this->id)->lockForUpdate()->first();
            
            if (!$payment) {
                return;
            }

            // Check if this notification has already been processed (idempotent)
            $currentTransactionStatus = $payment->transaction_status;
            $newTransactionStatus = $notification['transaction_status'];
            
            // If status hasn't changed, skip processing
            if ($currentTransactionStatus === $newTransactionStatus && 
                $payment->midtrans_response && 
                isset($payment->midtrans_response['transaction_id']) && 
                $payment->midtrans_response['transaction_id'] === ($notification['transaction_id'] ?? null)) {
                return;
            }

            // Determine status based on transaction_status
            $status = 'pending';
            if (in_array($newTransactionStatus, ['settlement', 'capture'])) {
                $status = 'paid';
            } elseif (in_array($newTransactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                $status = 'failed';
            }

            $payment->update([
                'transaction_status' => $newTransactionStatus,
                'status' => $status,
                'payment_type' => $notification['payment_type'] ?? null,
                'payment_method' => $notification['payment_type'] ?? null,
                'bank' => $notification['bank'] ?? null,
                'va_number' => $notification['va_number'] ?? null,
                'fraud_status' => $notification['fraud_status'] ?? null,
                'status_code' => $notification['status_code'] ?? null,
                'status_message' => $notification['status_message'] ?? null,
                'transaction_id' => $notification['transaction_id'] ?? null,
                'payment_code' => $notification['payment_code'] ?? null,
                'pdf_url' => $notification['pdf_url'] ?? null,
                'midtrans_response' => $notification,
            ]);

            // Update waktu pembayaran jika settlement
            if ($payment->isSuccess()) {
                $payment->update([
                    'paid_at' => now(),
                    // MODIFIED: Auto-confirm payment without admin intervention
                    'is_confirmed' => true,
                    'confirmed_at' => now(),
                    'confirmed_by' => null, // System auto-confirmation
                    'confirmation_notes' => 'Pembayaran dikonfirmasi otomatis oleh sistem setelah settlement'
                ]);

                // MODIFIED: Auto-confirm registration without admin intervention
                if ($payment->registration->status === 'pending') {
                    $payment->registration->update([
                        'status' => 'confirmed', // Changed from 'paid' to 'confirmed'
                        'confirmed_at' => now(),
                        'confirmed_by' => null, // System auto-confirmation
                    ]);

                    // Generate QR Code for confirmed registration
                    $payment->registration->generateQRCode();
                }

                // Store WhatsApp group link in session for display
                $payment->storeWhatsAppGroupLink();
            }
        });
    }

    /**
     * Cek apakah pembayaran berhasil
     * 
     * @return bool
     */
    public function isSuccess()
    {
        return in_array($this->transaction_status, ['settlement', 'capture']);
    }

    /**
     * Cek apakah pembayaran pending
     * 
     * @return bool
     */
    public function isPending()
    {
        return $this->transaction_status === 'pending';
    }

    /**
     * Cek apakah pembayaran gagal
     *
     * @return bool
     */
    public function isFailed()
    {
        return in_array($this->transaction_status, ['deny', 'cancel', 'expire', 'failure']);
    }

    /**
     * Store WhatsApp group link in session for display after payment
     *
     * @return void
     */
    protected function storeWhatsAppGroupLink()
    {
        $competition = $this->registration->competition;

        if ($competition && $competition->whatsapp_group_link) {
            // Store in session for display on success page
            session([
                'whatsapp_group_link' => $competition->whatsapp_group_link,
                'competition_name' => $competition->name,
                'payment_id' => $this->id
            ]);
        }
    }

    /**
     * Cek apakah pembayaran sudah expired
     * 
     * @return bool
     */
    public function isExpired()
    {
        return $this->transaction_status === 'expire' || 
               ($this->expired_at && now() > $this->expired_at);
    }

    /**
     * Accessor untuk status pembayaran dalam bahasa Indonesia
     * 
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->transaction_status) {
            case 'settlement':
            case 'capture':
                if ($this->is_confirmed) {
                    return 'Terkonfirmasi';
                } else {
                    return 'Menunggu Konfirmasi Admin';
                }
            case 'pending':
                return 'Menunggu Pembayaran';
            case 'deny':
                return 'Ditolak';
            case 'cancel':
                return 'Dibatalkan';
            case 'expire':
                return 'Kedaluwarsa';
            case 'failure':
                return 'Gagal';
            default:
                return 'Tidak Diketahui';
        }
    }

    /**
     * Accessor untuk CSS class status
     * 
     * @return string
     */
    public function getStatusClassAttribute()
    {
        switch ($this->transaction_status) {
            case 'settlement':
            case 'capture':
                if ($this->is_confirmed) {
                    return 'success';
                } else {
                    return 'info';
                }
            case 'pending':
                return 'warning';
            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failure':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    /**
     * Accessor untuk metode pembayaran dalam bahasa Indonesia
     *
     * @return string
     */
    public function getPaymentMethodLabelAttribute()
    {
        // Use payment_method column if available, otherwise use payment_type
        $paymentType = $this->attributes['payment_method'] ?? $this->payment_type;

        switch ($paymentType) {
            case 'bank_transfer':
                return 'Transfer Bank';
            case 'echannel':
                return 'Mandiri Bill Payment';
            case 'permata':
                return 'Permata Virtual Account';
            case 'bca':
                return 'BCA Virtual Account';
            case 'bni':
                return 'BNI Virtual Account';
            case 'bri':
                return 'BRI Virtual Account';
            case 'gopay':
                return 'GoPay';
            case 'shopeepay':
                return 'ShopeePay';
            case 'qris':
            case 'other_qris':
                return 'QRIS';
            case 'credit_card':
                return 'Kartu Kredit';
            case 'cstore':
            case 'indomaret':
                return 'Indomaret';
            case 'alfamart':
                return 'Alfamart';
            default:
                return $paymentType ? ucwords(str_replace('_', ' ', $paymentType)) : '-';
        }
    }

    /**
     * Get payment instruction text
     * 
     * @return string
     */
    public function getPaymentInstructionAttribute()
    {
        if ($this->payment_code) {
            return "Kode Pembayaran: {$this->payment_code}";
        }
        
        if ($this->pdf_url) {
            return "Unduh instruksi pembayaran";
        }
        
        return "Selesaikan pembayaran sesuai metode yang dipilih";
    }

    /**
     * Check if payment is successful but awaiting admin confirmation
     * 
     * @return bool
     */
    public function isAwaitingConfirmation()
    {
        return $this->isSuccess() && !$this->is_confirmed;
    }

    /**
     * Check if payment is confirmed by admin
     * 
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->is_confirmed;
    }
}
