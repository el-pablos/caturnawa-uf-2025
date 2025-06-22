<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $timestamp = now()->format('YmdHis');
        $random = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        return "UF2025-{$timestamp}-{$random}";
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
     * @param array $notification
     * @return void
     */
    public function updateFromMidtrans($notification)
    {
        // Determine status based on transaction_status
        $status = 'pending';
        if (in_array($notification['transaction_status'], ['settlement', 'capture'])) {
            $status = 'paid';
        } elseif (in_array($notification['transaction_status'], ['deny', 'cancel', 'expire', 'failure'])) {
            $status = 'failed';
        }

        $this->update([
            'transaction_status' => $notification['transaction_status'],
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
        if ($this->isSuccess()) {
            $this->update(['paid_at' => now()]);

            // Konfirmasi pendaftaran
            $this->registration->confirm();
        }
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
                return 'Berhasil';
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
                return 'success';
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
    public function getPaymentMethodAttribute()
    {
        switch ($this->payment_type) {
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
                return 'QRIS';
            case 'credit_card':
                return 'Kartu Kredit';
            case 'cstore':
                return 'Convenience Store';
            default:
                return $this->payment_type ? ucwords(str_replace('_', ' ', $this->payment_type)) : '-';
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
}
