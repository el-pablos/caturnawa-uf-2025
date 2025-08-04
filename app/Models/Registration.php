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
        'dynamic_data',
        
        // EDC-specific fields from DOCX
        'academic_year',
        'student_id_number',
        'major_study_program',
        'semester',
        'birth_place',
        'birth_date',
        'nationality',
        'religion',
        'address',
        'city',
        'postal_code',
        'province',
        'id_card_number',
        'id_card_photo',
        'blood_type',
        'health_conditions',
        'allergies',
        'medications',
        'dietary_restrictions',
        'emergency_contact_relationship',
        'emergency_contact_address',
        'photo_3x4',
        'student_id_card_photo',
        'university_letter',
        'health_certificate',
        'consent_form',
        'payment_receipt',
        'team_agreement_letter',
        'zoom_account_email',
        'previous_debate_experience',
        'motivation_letter',
        'preferred_debate_topics',
        'language_proficiency_level',
        'toefl_ielts_score',
        'toefl_ielts_certificate',
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
        'dynamic_data' => 'array',
        'birth_date' => 'date',
        'health_conditions' => 'array',
        'allergies' => 'array',
        'medications' => 'array',
        'dietary_restrictions' => 'array',
        'previous_debate_experience' => 'array',
        'preferred_debate_topics' => 'array',
        'toefl_ielts_score' => 'integer',
    ];

    /**
     * Konstanta untuk status pendaftaran
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
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
     * Scope untuk pendaftaran yang sudah dibayar (selesai)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_PAID);
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
     * Scope untuk pendaftaran yang sudah dibayar
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
     * Tandai sebagai sudah dibayar (selesai)
     * 
     * @return void
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'confirmed_at' => now(),
        ]);
        
    }

    /**
     * Check if registration is completed (paid)
     * 
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_PAID;
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
        if ($this->qr_code && $this->isCompleted()) {
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

    /**
     * EDC-specific validation rules
     * 
     * @return array
     */
    public static function getEdcValidationRules()
    {
        return [
            // Team Information
            'team_name' => 'required|string|max:255',
            'team_members' => 'required|array|min:2|max:2', // Exactly 2 members untuk EDC
            
            // Team Members Basic Info
            'team_members.*.name' => 'required|string|max:255',
            'team_members.*.email' => 'required|email|max:255',
            'team_members.*.phone' => 'required|string|max:20',
            'team_members.*.university' => 'required|string|max:255',
            'team_members.*.faculty' => 'required|string|max:255',
            'team_members.*.study_program' => 'required|string|max:255',
            'team_members.*.student_id' => 'required|string|max:50',
            'team_members.*.academic_year' => 'required|string|max:20',
            'team_members.*.semester' => 'required|integer|min:1|max:14',
            'team_members.*.speaker_position' => 'required|in:first_speaker,second_speaker',
            
            // Personal Information
            'team_members.*.birth_place' => 'required|string|max:255',
            'team_members.*.birth_date' => 'required|date|before:today',
            'team_members.*.nationality' => 'required|string|max:100',
            'team_members.*.religion' => 'required|string|max:50',
            'team_members.*.gender' => 'required|in:male,female',
            'team_members.*.id_card_number' => 'required|string|max:20',
            
            // Address Information
            'team_members.*.address' => 'required|string|max:500',
            'team_members.*.city' => 'required|string|max:100',
            'team_members.*.province' => 'required|string|max:100',
            'team_members.*.postal_code' => 'required|string|max:10',
            
            // Health Information
            'team_members.*.blood_type' => 'required|in:A,B,AB,O',
            'team_members.*.health_conditions' => 'nullable|array',
            'team_members.*.allergies' => 'nullable|array',
            'team_members.*.medications' => 'nullable|array',
            'team_members.*.dietary_restrictions' => 'nullable|array',
            
            // Emergency Contact
            'team_members.*.emergency_contact' => 'required|string|max:255',
            'team_members.*.emergency_phone' => 'required|string|max:20',
            'team_members.*.emergency_contact_relationship' => 'required|string|max:100',
            'team_members.*.emergency_contact_address' => 'required|string|max:500',
            
            // Debate-specific Information
            'team_members.*.zoom_account_email' => 'required|email|max:255',
            'team_members.*.previous_debate_experience' => 'nullable|array',
            'team_members.*.motivation_letter' => 'required|string|max:2000',
            'team_members.*.preferred_debate_topics' => 'nullable|array',
            'team_members.*.language_proficiency_level' => 'required|in:beginner,intermediate,advanced,native',
            'team_members.*.toefl_ielts_score' => 'nullable|integer|min:0|max:990',
            
            // Required Documents
            'team_members.*.foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'team_members.*.photo_3x4' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'team_members.*.id_card_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'team_members.*.student_id_card_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'team_members.*.university_letter' => 'required|mimes:pdf|max:5120',
            'team_members.*.health_certificate' => 'required|mimes:pdf|max:5120',
            'team_members.*.consent_form' => 'required|mimes:pdf|max:5120',
            'team_members.*.toefl_ielts_certificate' => 'nullable|mimes:pdf|max:5120',
            
            // Team Documents
            'team_agreement_letter' => 'required|mimes:pdf|max:5120',
            'logo_instansi' => 'required|image|mimes:jpeg,png,svg|max:2048',
        ];
    }

    /**
     * EDC-specific validation messages
     * 
     * @return array
     */
    public static function getEdcValidationMessages()
    {
        return [
            // Team validation messages
            'team_name.required' => 'Nama tim harus diisi',
            'team_members.required' => 'Anggota tim harus diisi',
            'team_members.min' => 'Tim EDC harus terdiri dari 2 orang',
            'team_members.max' => 'Tim EDC maksimal 2 orang',
            
            // Basic information messages
            'team_members.*.name.required' => 'Nama anggota tim harus diisi',
            'team_members.*.email.required' => 'Email anggota tim harus diisi',
            'team_members.*.email.email' => 'Format email tidak valid',
            'team_members.*.phone.required' => 'Nomor telepon anggota tim harus diisi',
            'team_members.*.university.required' => 'Universitas harus diisi',
            'team_members.*.faculty.required' => 'Fakultas harus diisi',
            'team_members.*.study_program.required' => 'Program studi harus diisi',
            'team_members.*.student_id.required' => 'NIM harus diisi',
            'team_members.*.academic_year.required' => 'Tahun akademik harus diisi',
            'team_members.*.semester.required' => 'Semester harus diisi',
            'team_members.*.semester.min' => 'Semester minimal 1',
            'team_members.*.semester.max' => 'Semester maksimal 14',
            'team_members.*.speaker_position.required' => 'Posisi speaker harus dipilih',
            'team_members.*.speaker_position.in' => 'Posisi speaker harus First Speaker atau Second Speaker',
            
            // Personal information messages
            'team_members.*.birth_place.required' => 'Tempat lahir harus diisi',
            'team_members.*.birth_date.required' => 'Tanggal lahir harus diisi',
            'team_members.*.birth_date.before' => 'Tanggal lahir harus sebelum hari ini',
            'team_members.*.nationality.required' => 'Kebangsaan harus diisi',
            'team_members.*.religion.required' => 'Agama harus diisi',
            'team_members.*.gender.required' => 'Jenis kelamin harus dipilih',
            'team_members.*.gender.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
            'team_members.*.id_card_number.required' => 'Nomor KTP harus diisi',
            
            // Address messages
            'team_members.*.address.required' => 'Alamat harus diisi',
            'team_members.*.city.required' => 'Kota harus diisi',
            'team_members.*.province.required' => 'Provinsi harus diisi',
            'team_members.*.postal_code.required' => 'Kode pos harus diisi',
            
            // Health information messages
            'team_members.*.blood_type.required' => 'Golongan darah harus dipilih',
            'team_members.*.blood_type.in' => 'Golongan darah harus A, B, AB, atau O',
            
            // Emergency contact messages
            'team_members.*.emergency_contact.required' => 'Kontak darurat harus diisi',
            'team_members.*.emergency_phone.required' => 'Nomor telepon darurat harus diisi',
            'team_members.*.emergency_contact_relationship.required' => 'Hubungan kontak darurat harus diisi',
            'team_members.*.emergency_contact_address.required' => 'Alamat kontak darurat harus diisi',
            
            // Debate-specific messages
            'team_members.*.zoom_account_email.required' => 'Email akun Zoom harus diisi',
            'team_members.*.zoom_account_email.email' => 'Format email Zoom tidak valid',
            'team_members.*.motivation_letter.required' => 'Surat motivasi harus diisi',
            'team_members.*.motivation_letter.max' => 'Surat motivasi maksimal 2000 karakter',
            'team_members.*.language_proficiency_level.required' => 'Level kemampuan bahasa harus dipilih',
            'team_members.*.language_proficiency_level.in' => 'Level bahasa harus Beginner, Intermediate, Advanced, atau Native',
            'team_members.*.toefl_ielts_score.integer' => 'Skor TOEFL/IELTS harus berupa angka',
            'team_members.*.toefl_ielts_score.min' => 'Skor TOEFL/IELTS minimal 0',
            'team_members.*.toefl_ielts_score.max' => 'Skor TOEFL/IELTS maksimal 990',
            
            // Document validation messages
            'team_members.*.foto.required' => 'Foto anggota tim harus diunggah',
            'team_members.*.foto.image' => 'File foto harus berupa gambar',
            'team_members.*.foto.mimes' => 'Format foto harus JPEG, PNG, atau JPG',
            'team_members.*.foto.max' => 'Ukuran foto maksimal 2MB',
            'team_members.*.photo_3x4.required' => 'Foto 3x4 harus diunggah',
            'team_members.*.photo_3x4.image' => 'File foto 3x4 harus berupa gambar',
            'team_members.*.photo_3x4.mimes' => 'Format foto 3x4 harus JPEG, PNG, atau JPG',
            'team_members.*.photo_3x4.max' => 'Ukuran foto 3x4 maksimal 2MB',
            'team_members.*.id_card_photo.required' => 'Foto KTP harus diunggah',
            'team_members.*.id_card_photo.image' => 'File foto KTP harus berupa gambar',
            'team_members.*.id_card_photo.mimes' => 'Format foto KTP harus JPEG, PNG, atau JPG',
            'team_members.*.id_card_photo.max' => 'Ukuran foto KTP maksimal 2MB',
            'team_members.*.student_id_card_photo.required' => 'Foto KTM harus diunggah',
            'team_members.*.student_id_card_photo.image' => 'File foto KTM harus berupa gambar',
            'team_members.*.student_id_card_photo.mimes' => 'Format foto KTM harus JPEG, PNG, atau JPG',
            'team_members.*.student_id_card_photo.max' => 'Ukuran foto KTM maksimal 2MB',
            'team_members.*.university_letter.required' => 'Surat keterangan universitas harus diunggah',
            'team_members.*.university_letter.mimes' => 'Format surat universitas harus PDF',
            'team_members.*.university_letter.max' => 'Ukuran surat universitas maksimal 5MB',
            'team_members.*.health_certificate.required' => 'Sertifikat kesehatan harus diunggah',
            'team_members.*.health_certificate.mimes' => 'Format sertifikat kesehatan harus PDF',
            'team_members.*.health_certificate.max' => 'Ukuran sertifikat kesehatan maksimal 5MB',
            'team_members.*.consent_form.required' => 'Form persetujuan harus diunggah',
            'team_members.*.consent_form.mimes' => 'Format form persetujuan harus PDF',
            'team_members.*.consent_form.max' => 'Ukuran form persetujuan maksimal 5MB',
            'team_members.*.toefl_ielts_certificate.mimes' => 'Format sertifikat TOEFL/IELTS harus PDF',
            'team_members.*.toefl_ielts_certificate.max' => 'Ukuran sertifikat TOEFL/IELTS maksimal 5MB',
            
            // Team document messages
            'team_agreement_letter.required' => 'Surat kesepakatan tim harus diunggah',
            'team_agreement_letter.mimes' => 'Format surat kesepakatan tim harus PDF',
            'team_agreement_letter.max' => 'Ukuran surat kesepakatan tim maksimal 5MB',
            'logo_instansi.required' => 'Logo institusi harus diunggah',
            'logo_instansi.image' => 'File logo institusi harus berupa gambar',
            'logo_instansi.mimes' => 'Format logo institusi harus JPEG, PNG, atau SVG',
            'logo_instansi.max' => 'Ukuran logo institusi maksimal 2MB',
        ];
    }

    /**
     * Validate EDC team name untuk SARA compliance
     * 
     * @param string $teamName
     * @return array
     */
    public static function validateEdcTeamName($teamName)
    {
        $errors = [];
        
        // Comprehensive SARA-related words filtering
        $forbiddenWords = [
            // Ethnic-related terms (Suku)
            'pribumi', 'asli', 'pendatang', 'asing', 'non-pribumi', 'minoritas', 'mayoritas',
            'batak', 'jawa', 'sunda', 'minang', 'padang', 'bugis', 'makassar', 'dayak', 'papua',
            'tionghoa', 'cina', 'arab', 'india', 'melayu', 'betawi',
            
            // Religious terms (Agama)
            'kafir', 'kuffar', 'musyrik', 'sesat', 'murtad', 'bid\'ah', 'haram', 'najis',
            'kristen', 'katolik', 'protestan', 'buddha', 'hindu', 'konghucu', 'yahudi',
            'islam', 'muslim', 'muslimah', 'islamis', 'jihad', 'syariah',
            
            // Race-related terms (Ras) 
            'kulit putih', 'kulit hitam', 'kulit sawo', 'berkulit', 'bermata',
            'asia', 'eropa', 'afrika', 'amerika', 'australia', 'mongol', 'kaukasia',
            
            // Inter-group conflict terms (Antar-golongan)
            'golongan', 'partai', 'organisasi', 'aliran', 'sekte', 'faksi', 'kubu',
            'pki', 'komunis', 'liberal', 'radikal', 'konservatif', 'fundamentalis',
            'ormas', 'laskar', 'milisi', 'preman', 'gangster',
            
            // Discriminatory terms
            'diskriminasi', 'rasisme', 'intoleransi', 'kebencian', 'permusuhan',
            'inferior', 'superior', 'rendah', 'tinggi', 'mulia', 'hina',
            
            // Offensive slurs and derogatory terms
            'babi', 'anjing', 'monyet', 'kera', 'bangsat', 'setan', 'iblis',
            'tolol', 'bodoh', 'goblok', 'dungu', 'idiot', 'stupid',
            
            // Political and controversial terms
            'separatis', 'makar', 'pemberontak', 'teroris', 'radikal',
            'komunis', 'sosialis', 'kapitalis', 'imperialis', 'kolonialis',
        ];
        
        $teamNameLower = strtolower(trim($teamName));
        
        // Check for forbidden words
        foreach ($forbiddenWords as $word) {
            if (strpos($teamNameLower, strtolower($word)) !== false) {
                $errors[] = 'Nama tim mengandung unsur SARA atau kata yang tidak pantas. Silakan gunakan nama yang lebih netral dan positif.';
                break;
            }
        }
        
        // Check for inappropriate patterns
        $inappropriatePatterns = [
            '/\b(anti|benci|tolak)\s+(islam|kristen|buddha|hindu|konghucu)\b/i',
            '/\b(hidup|mati|bunuh)\b/i',
            '/\b(perang|pertempuran|serang|hancur)\b/i',
            '/\b(sara|rasisme|diskriminasi)\b/i',
        ];
        
        foreach ($inappropriatePatterns as $pattern) {
            if (preg_match($pattern, $teamNameLower)) {
                $errors[] = 'Nama tim mengandung kata atau frasa yang tidak pantas untuk kompetisi akademik.';
                break;
            }
        }
        
        // Check team name length and format
        if (strlen(trim($teamName)) < 3) {
            $errors[] = 'Nama tim minimal 3 karakter';
        }
        
        if (strlen(trim($teamName)) > 50) {
            $errors[] = 'Nama tim maksimal 50 karakter';
        }
        
        // Check if team name relates to UNAS FEST 2025 theme or academic/debate themes
        $themeWords = [
            // Event-related
            'unas', 'fest', '2025', 'caturnawa', 'festival',
            
            // Debate-related
            'debate', 'debat', 'english', 'speaker', 'argument', 'motion', 'eloquent',
            'rhetoric', 'oratory', 'discourse', 'parliam', 'oxford', 'cambridge',
            'british', 'american', 'australs', 'asian', 'world', 'champion',
            
            // Academic-related
            'academic', 'akademik', 'scholar', 'student', 'mahasiswa', 'university',
            'college', 'campus', 'education', 'knowledge', 'wisdom', 'smart',
            'brilliant', 'genius', 'intellectual', 'thinker', 'philosopher',
            
            // Positive values
            'unity', 'persatuan', 'harmoni', 'peace', 'damai', 'toleransi',
            'diversity', 'keberagaman', 'inklusif', 'pluralism', 'bhinneka',
            'pancasila', 'nkri', 'indonesia', 'nusantara', 'archipelago',
            
            // Achievement-related
            'winner', 'champion', 'victory', 'success', 'excellence', 'outstanding',
            'superior', 'elite', 'premier', 'ultimate', 'master', 'expert',
            'legend', 'hero', 'star', 'ace', 'pro', 'pioneer', 'innovator',
        ];
        
        $hasThemeRelation = false;
        
        foreach ($themeWords as $theme) {
            if (strpos($teamNameLower, strtolower($theme)) !== false) {
                $hasThemeRelation = true;
                break;
            }
        }
        
        // Also check for creative combinations or academic terms
        $academicPatterns = [
            '/\b(team|tim|squad|group|crew|alliance|united|union)\b/i',
            '/\b(future|masa depan|tomorrow|esok|harapan|hope)\b/i',
            '/\b(young|muda|youth|generasi|generation)\b/i',
            '/\b(voice|suara|speak|bicara|express|ekspres)\b/i',
        ];
        
        foreach ($academicPatterns as $pattern) {
            if (preg_match($pattern, $teamNameLower)) {
                $hasThemeRelation = true;
                break;
            }
        }
        
        if (!$hasThemeRelation) {
            $errors[] = 'Nama tim sebaiknya berhubungan dengan tema UNAS FEST 2025, debate, atau nilai-nilai akademik dan positif.';
        }
        
        // Check for profanity or inappropriate content
        if (preg_match('/\b(fuck|shit|damn|hell|ass|bitch|bastard|crap)\b/i', $teamNameLower)) {
            $errors[] = 'Nama tim mengandung kata-kata tidak pantas dalam bahasa asing.';
        }
        
        return $errors;
    }

    /**
     * Validate same university requirement untuk EDC
     * 
     * @param array $teamMembers
     * @return array
     */
    public static function validateSameUniversity($teamMembers)
    {
        $errors = [];
        
        if (count($teamMembers) < 2) {
            return $errors;
        }
        
        $firstUniversity = $teamMembers[0]['university'] ?? '';
        
        foreach ($teamMembers as $index => $member) {
            $university = $member['university'] ?? '';
            if ($university !== $firstUniversity) {
                $errors[] = 'Semua anggota tim harus berasal dari universitas yang sama';
                break;
            }
        }
        
        return $errors;
    }

    /**
     * Validate speaker positions untuk EDC
     * 
     * @param array $teamMembers
     * @return array
     */
    public static function validateSpeakerPositions($teamMembers)
    {
        $errors = [];
        $positions = [];
        
        foreach ($teamMembers as $member) {
            $position = $member['speaker_position'] ?? '';
            if (in_array($position, $positions)) {
                $errors[] = 'Setiap anggota tim harus memiliki posisi speaker yang berbeda';
                break;
            }
            $positions[] = $position;
        }
        
        // Check if both required positions are filled
        $requiredPositions = ['first_speaker', 'second_speaker'];
        foreach ($requiredPositions as $required) {
            if (!in_array($required, $positions)) {
                $errors[] = 'Tim harus memiliki First Speaker dan Second Speaker';
                break;  
            }
        }
        
        return $errors;
    }

    /**
     * Get EDC pricing berdasarkan timeline
     * 
     * @return array
     */
    public static function getCurrentEdcPricing()
    {
        $now = now();
        
        if ($now <= \Carbon\Carbon::parse(\App\Models\Competition::EDC_TIMELINE_2025['early_bird_end'])) {
            return [
                'phase' => 'early_bird',
                'amount' => \App\Models\Competition::EDC_PRICING_2025['early_bird'],
                'phase_name' => 'Early Bird',
                'deadline' => \App\Models\Competition::EDC_TIMELINE_2025['early_bird_end']
            ];
        }
        
        if ($now <= \Carbon\Carbon::parse(\App\Models\Competition::EDC_TIMELINE_2025['phase_1_end'])) {
            return [
                'phase' => 'phase_1',
                'amount' => \App\Models\Competition::EDC_PRICING_2025['phase_1'],
                'phase_name' => 'Phase 1',
                'deadline' => \App\Models\Competition::EDC_TIMELINE_2025['phase_1_end']
            ];
        }
        
        if ($now <= \Carbon\Carbon::parse(\App\Models\Competition::EDC_TIMELINE_2025['phase_2_end'])) {
            return [
                'phase' => 'phase_2',
                'amount' => \App\Models\Competition::EDC_PRICING_2025['phase_2'],
                'phase_name' => 'Phase 2',
                'deadline' => \App\Models\Competition::EDC_TIMELINE_2025['phase_2_end']
            ];
        }
        
        return [
            'phase' => 'closed',
            'amount' => 0,
            'phase_name' => 'Registration Closed',
            'deadline' => null
        ];
    }

    /**
     * Check if this is EDC registration
     * 
     * @return bool
     */
    public function isEdcRegistration()
    {
        return $this->competition && $this->competition->category === 'event_debate';
    }
}
