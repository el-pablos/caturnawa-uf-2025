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
        'registration_deadline',
        'round1_date',
        'semifinal_date',
        'final_date',
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
        'prize_amount',
        'type',
        'short_description',
        'contact_person',
        'contact_email',
        'contact_phone',
        'terms_conditions',
        'judging_criteria',
        'certificate_template',
        'is_featured',
        'view_count',
        'show_leaderboard',
        'registration_count',
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
        'registration_deadline' => 'datetime',
        'round1_date' => 'datetime',
        'semifinal_date' => 'datetime',
        'final_date' => 'datetime',
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
        'show_leaderboard' => 'boolean',
        'early_bird_price' => 'decimal:2',
        'prize_amount' => 'decimal:2',
        'judging_criteria' => 'array',
        'is_featured' => 'boolean',
        'view_count' => 'integer',
        'registration_count' => 'integer',
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

    /**
     * Get days left until registration deadline
     *
     * @return int|null
     */
    public function getDaysLeftAttribute()
    {
        if (!$this->registration_deadline) {
            return null;
        }

        $now = now();
        $deadline = $this->registration_deadline;

        if ($deadline <= $now) {
            return 0;
        }

        return $now->diffInDays($deadline);
    }

    /**
     * Get competition timeline
     *
     * @return array
     */
    public function getTimelineAttribute()
    {
        $timeline = [];

        if ($this->registration_start) {
            $timeline[] = [
                'title' => 'Pendaftaran Dibuka',
                'date' => $this->registration_start,
                'status' => $this->registration_start <= now() ? 'completed' : 'upcoming',
                'icon' => 'bi-calendar-plus',
                'color' => 'success'
            ];
        }

        if ($this->registration_deadline) {
            $timeline[] = [
                'title' => 'Batas Akhir Pendaftaran',
                'date' => $this->registration_deadline,
                'status' => $this->registration_deadline <= now() ? 'completed' : 'upcoming',
                'icon' => 'bi-calendar-x',
                'color' => 'warning'
            ];
        }

        if ($this->round1_date) {
            $timeline[] = [
                'title' => 'Babak Penyisihan',
                'date' => $this->round1_date,
                'status' => $this->round1_date <= now() ? 'completed' : 'upcoming',
                'icon' => 'bi-trophy',
                'color' => 'primary'
            ];
        }

        if ($this->semifinal_date) {
            $timeline[] = [
                'title' => 'Semifinal',
                'date' => $this->semifinal_date,
                'status' => $this->semifinal_date <= now() ? 'completed' : 'upcoming',
                'icon' => 'bi-award',
                'color' => 'info'
            ];
        }

        if ($this->final_date) {
            $timeline[] = [
                'title' => 'Final',
                'date' => $this->final_date,
                'status' => $this->final_date <= now() ? 'completed' : 'upcoming',
                'icon' => 'bi-trophy-fill',
                'color' => 'danger'
            ];
        }

        if ($this->result_announcement) {
            $timeline[] = [
                'title' => 'Pengumuman Hasil',
                'date' => $this->result_announcement,
                'status' => $this->result_announcement <= now() ? 'completed' : 'upcoming',
                'icon' => 'bi-megaphone',
                'color' => 'success'
            ];
        }

        // Sort by date
        usort($timeline, function ($a, $b) {
            return $a['date']->timestamp <=> $b['date']->timestamp;
        });

        return $timeline;
    }
}
