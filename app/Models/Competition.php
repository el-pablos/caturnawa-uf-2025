<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Competition untuk mengelola data kompetisi/lomba
 * 
 * Kelas ini menangani semua operasi CRUD untuk kompetisi
 * termasuk kategori, harga, dan periode pendaftaran
 */
class Competition extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atribut yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'theme',
        'price',
        'early_bird_price',
        'early_bird_deadline',
        'registration_start',
        'registration_end',
        'competition_start',
        'competition_end',
        'max_participants',
        'min_team_members',
        'max_team_members',
        'requirements',
        'prizes',
        'rules',
        'image',
        'is_active',
        'status',
        'is_team_competition',
        'allow_individual',
        'submission_deadline',
        'result_announcement',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'early_bird_deadline' => 'datetime',
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'competition_start' => 'datetime',
        'competition_end' => 'datetime',
        'submission_deadline' => 'datetime',
        'result_announcement' => 'datetime',
        'requirements' => 'array',
        'prizes' => 'array',
        'rules' => 'array',
        'is_active' => 'boolean',
        'is_team_competition' => 'boolean',
        'allow_individual' => 'boolean',
        'price' => 'decimal:2',
        'early_bird_price' => 'decimal:2',
    ];

    /**
     * Konstanta untuk kategori kompetisi
     */
    const CATEGORIES = [
        'biodiversity' => 'Bio-diversity',
        'health' => 'Health',
        'technology' => 'Technology',
    ];

    /**
     * Relasi dengan model Registration (pendaftaran)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Relasi dengan model Score (penilaian)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    /**
     * Relasi dengan model Submission (karya peserta)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Relasi many-to-many dengan User (juries)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function juries()
    {
        return $this->belongsToMany(User::class, 'competition_juries', 'competition_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Relasi untuk registrasi yang sudah dikonfirmasi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function confirmedRegistrations()
    {
        return $this->hasMany(Registration::class)->where('status', 'confirmed');
    }

    /**
     * Scope untuk kompetisi yang aktif
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk kompetisi yang sedang buka pendaftaran
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpenRegistration($query)
    {
        return $query->where('is_active', true)
            ->where('registration_start', '<=', now())
            ->where('registration_end', '>=', now());
    }

    /**
     * Scope berdasarkan kategori
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Accessor untuk mendapatkan URL gambar kompetisi
     * 
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/competitions/' . $this->image);
        }
        
        return asset('images/default-competition.png');
    }

    /**
     * Accessor untuk status pendaftaran
     * 
     * @return string
     */
    public function getRegistrationStatusAttribute()
    {
        $now = now();
        
        if ($now < $this->registration_start) {
            return 'upcoming';
        } elseif ($now > $this->registration_end) {
            return 'closed';
        } else {
            return 'open';
        }
    }

    /**
     * Accessor untuk mendapatkan harga yang berlaku
     * 
     * @return float
     */
    public function getCurrentPriceAttribute()
    {
        if ($this->early_bird_deadline && now() <= $this->early_bird_deadline) {
            return $this->early_bird_price ?? $this->price;
        }
        
        return $this->price;
    }

    /**
     * Cek apakah masih dalam periode early bird
     * 
     * @return bool
     */
    public function isEarlyBird()
    {
        return $this->early_bird_deadline && now() <= $this->early_bird_deadline;
    }

    /**
     * Cek apakah pendaftaran masih terbuka
     * 
     * @return bool
     */
    public function isRegistrationOpen()
    {
        return $this->is_active && 
               now() >= $this->registration_start && 
               now() <= $this->registration_end;
    }

    /**
     * Cek apakah sudah mencapai batas maksimal peserta
     * 
     * @return bool
     */
    public function isFullyBooked()
    {
        if (!$this->max_participants) {
            return false;
        }
        
        return $this->registrations()->where('status', 'confirmed')->count() >= $this->max_participants;
    }

    /**
     * Mendapatkan jumlah peserta terdaftar
     * 
     * @return int
     */
    public function getRegisteredParticipantsCount()
    {
        return $this->registrations()->where('status', 'confirmed')->count();
    }

    /**
     * Mendapatkan total pendapatan dari kompetisi
     * 
     * @return float
     */
    public function getTotalRevenue()
    {
        return $this->registrations()
            ->whereHas('payment', function($query) {
                $query->where('status', 'success');
            })
            ->sum('amount');
    }
}
