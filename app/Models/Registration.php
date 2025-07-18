<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PricingPhase;

/**
 * Model Registration untuk mengelola data pendaftaran kompetisi
 * 
 * Kelas ini menangani proses pendaftaran peserta ke kompetisi
 * termasuk status pendaftaran dan pembayaran
 */
class Registration extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'competition_id',
        'registration_number',
        'team_name',
        'team_members',
        'institution',
        'logo_instansi',
        'phone',
        'gender',
        'education_level',
        'participant_category',
        'pricing_phase',
        'emergency_contact',
        'emergency_phone',
        'special_needs',
        'amount',
        'original_price',
        'status',
        'registered_at',
        'confirmed_at',
        'cancelled_at',
        'cancelled_reason',
        'reopened_at',
        'reopened_by',
        'ticket_code',
        'qr_code',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'team_members' => 'array',
        'registered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'reopened_at' => 'datetime',
        'amount' => 'decimal:2',
        'original_price' => 'decimal:2',
    ];

    /**
     * Konstanta untuk status pendaftaran
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    /**
     * Boot method untuk generate registration number otomatis
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($registration) {
            if (!$registration->registration_number) {
                $registration->registration_number = $registration->generateRegistrationNumber();
            }
            
            if (!$registration->ticket_code) {
                $registration->ticket_code = $registration->generateTicketCode();
            }
        });
    }

    /**
     * Relasi dengan model User (peserta)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan model RegistrationDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(RegistrationDocument::class);
    }

    /**
     * Relasi dengan model Competition (kompetisi)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Relasi dengan model Payment (pembayaran)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Relasi dengan model Submission (karya)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function submission()
    {
        return $this->hasOne(Submission::class);
    }

    /**
     * Relasi dengan model Submission (karya) - plural
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Relasi dengan model TeamMatchup (penjadwalan tim dalam pertandingan)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamMatchups()
    {
        return $this->hasMany(TeamMatchup::class);
    }

    /**
     * Relasi dengan model TeamMember (anggota tim)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * Scope untuk pendaftaran yang dikonfirmasi
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    /**
     * Scope untuk pendaftaran yang pending
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope untuk pendaftaran yang sudah dibayar tapi belum dikonfirmasi
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Generate nomor pendaftaran unik
     * 
     * @return string
     */
    protected function generateRegistrationNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        // Format: UF2025-MM-XXXX
        $prefix = "UF{$year}-{$month}-";
        
        $lastNumber = static::where('registration_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();
            
        if ($lastNumber) {
            $number = intval(substr($lastNumber->registration_number, -4)) + 1;
        } else {
            $number = 1;
        }
        
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate kode tiket unik
     * 
     * @return string
     */
    protected function generateTicketCode()
    {
        do {
            $code = 'TICKET-' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (static::where('ticket_code', $code)->exists());
        
        return $code;
    }

    /**
     * Konfirmasi pendaftaran
     * 
     * @return void
     */
    public function confirm()
    {
        $this->update([
            'status' => self::STATUS_CONFIRMED,
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Check if registration is paid but not confirmed
     * 
     * @return bool
     */
    public function isPaidButNotConfirmed()
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Check if registration is confirmed
     * 
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    /**
     * Cek apakah pendaftaran sudah dikonfirmasi
     * 
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    /**
     * Cek apakah pendaftaran masih pending
     * 
     * @return bool
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Cek apakah pembayaran sudah berhasil
     * 
     * @return bool
     */
    public function isPaid()
    {
        return $this->payment && $this->payment->status === 'success';
    }

    /**
     * Accessor untuk URL QR Code
     * 
     * @return string|null
     */
    public function getQrCodeUrlAttribute()
    {
        if ($this->qr_code && $this->isConfirmed()) {
            // Jika qr_code berisi path file
            if (str_contains($this->qr_code, 'qrcodes/')) {
                return asset('storage/' . $this->qr_code);
            }
            // Jika qr_code berisi SVG data langsung
            return 'data:image/svg+xml;base64,' . base64_encode($this->qr_code);
        }
        
        return null;
    }

    /**
     * Accessor untuk mendapatkan nama tim atau peserta
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->team_name ?: $this->user->name;
    }

    /**
     * Generate QR Code untuk registrasi
     *
     * @return void
     */
    public function generateQRCode()
    {
        if (!$this->isConfirmed()) {
            return;
        }

        try {
            // Data yang akan di-encode dalam QR Code
            $qrData = $this->registration_number;

            // Generate QR Code menggunakan library SimpleSoftwareIO/simple-qrcode
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate($qrData);

            // Simpan QR Code sebagai SVG string
            $this->update(['qr_code' => $qrCode]);

        } catch (\Exception $e) {
            \Log::error('Failed to generate QR Code for registration ' . $this->id . ': ' . $e->getMessage());
        }
    }

    /**
     * Cancel pendaftaran
     *
     * @return void
     */
    public function cancel()
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancelled_reason' => 'Dibatalkan oleh peserta'
        ]);
    }

    /**
     * Reopen pendaftaran yang dibatalkan (hanya admin)
     *
     * @return void
     */
    public function reopen()
    {
        $this->update([
            'status' => self::STATUS_PENDING,
            'cancelled_at' => null,
            'cancelled_reason' => null,
            'reopened_at' => now(),
            'reopened_by' => auth()->id()
        ]);
    }

    /**
     * Expire pendaftaran yang belum dibayar
     *
     * @return void
     */
    public function expire()
    {
        $this->update(['status' => self::STATUS_EXPIRED]);
    }

    /**
     * Cek apakah pendaftaran sudah expired
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    /**
     * Cek apakah pendaftaran dibatalkan
     *
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Cek apakah user bisa mendaftar lagi di kompetisi ini
     *
     * @param int $userId
     * @param int $competitionId
     * @return bool
     */
    public static function canRegisterAgain($userId, $competitionId)
    {
        $cancelledRegistration = self::where('user_id', $userId)
            ->where('competition_id', $competitionId)
            ->where('status', self::STATUS_CANCELLED)
            ->first();

        // Jika tidak ada registrasi yang dibatalkan, bisa daftar
        if (!$cancelledRegistration) {
            return true;
        }

        // Jika ada registrasi yang dibatalkan, tidak bisa daftar lagi
        // kecuali admin sudah reopen
        return false;
    }

    /**
     * Konstanta untuk kategori peserta
     */
    const PARTICIPANT_CATEGORIES = [
        'unas_student' => 'Mahasiswa UNAS',
        'external_student' => 'Mahasiswa Eksternal',
        'high_school_student' => 'Siswa SMA/SMK',
    ];

    /**
     * Hitung harga berdasarkan kategori peserta dan fase pricing saat ini
     *
     * @param string $participantCategory
     * @return array
     */
    public static function calculatePrice($participantCategory)
    {
        $currentPhase = PricingPhase::getCurrentPhaseForCategory($participantCategory);

        if (!$currentPhase) {
            // Fallback ke harga regular jika tidak ada fase yang aktif
            $regularPrices = [
                'unas_student' => 200000,      // Rp 200.000
                'external_student' => 300000,  // Rp 300.000
                'high_school_student' => 75000, // Rp 75.000
            ];

            return [
                'amount' => $regularPrices[$participantCategory] ?? 300000,
                'phase' => 'regular',
                'phase_name' => 'Harga Regular',
                'original_price' => $regularPrices[$participantCategory] ?? 300000,
            ];
        }

        return [
            'amount' => $currentPhase->amount,
            'phase' => $currentPhase->phase_name,
            'phase_name' => $currentPhase->phase_display_name,
            'original_price' => $currentPhase->amount,
        ];
    }

    /**
     * Set harga berdasarkan kategori peserta
     *
     * @param string $participantCategory
     * @return void
     */
    public function setPriceByCategory($participantCategory)
    {
        $priceData = self::calculatePrice($participantCategory);

        $this->participant_category = $participantCategory;
        $this->amount = $priceData['amount'];
        $this->pricing_phase = $priceData['phase'];
        $this->original_price = $priceData['original_price'];
    }

    /**
     * Accessor untuk nama kategori peserta yang mudah dibaca
     *
     * @return string
     */
    public function getParticipantCategoryNameAttribute()
    {
        return self::PARTICIPANT_CATEGORIES[$this->participant_category] ?? $this->participant_category;
    }

    /**
     * Accessor untuk nama fase pricing yang mudah dibaca
     *
     * @return string
     */
    public function getPricingPhaseNameAttribute()
    {
        $phaseNames = [
            'early_bird' => 'Early Bird',
            'phase_1' => 'Phase 1',
            'phase_2' => 'Phase 2',
            'phase_3' => 'Phase 3',
            'default' => 'Harga Default',
        ];

        return $phaseNames[$this->pricing_phase] ?? $this->pricing_phase;
    }

    /**
     * Cek apakah mendapat harga early bird atau diskon
     *
     * @return bool
     */
    public function hasDiscount()
    {
        return $this->pricing_phase === 'early_bird' ||
               ($this->original_price && $this->amount < $this->original_price);
    }

    /**
     * Hitung persentase diskon jika ada
     *
     * @return float|null
     */
    public function getDiscountPercentage()
    {
        if (!$this->hasDiscount() || !$this->original_price) {
            return null;
        }

        return round((($this->original_price - $this->amount) / $this->original_price) * 100, 1);
    }
}
