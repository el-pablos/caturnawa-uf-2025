<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Model User untuk mengelola data pengguna sistem
 * 
 * Kelas ini menangani autentikasi, otorisasi, dan relasi
 * dengan data peserta lomba Caturnawa UNAS FEST 2025
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Atribut yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'participant_status',
        'institution',
        'bio',
        'student_id',
        'birth_date',
        'gender',
        'address',
        'city',
        'province',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'linkedin_url',
        'twitter_url',
        'instagram_url',
        'facebook_url',
        'github_url',
        'website_url',
        'badges',
        'profile_completion',
        'is_active',
        'email_verified_at',
    ];

    /**
     * Fields yang hanya bisa diisi oleh admin/system
     */
    protected $adminFillable = [
        'is_active',
        'email_verified_at',
        'last_login_at',
        'is_account_active',
        'account_activated_at',
        'activated_by',
        'activation_notes',
    ];

    /**
     * Atribut yang disembunyikan untuk serialisasi
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'birth_date' => 'date',
        'is_account_active' => 'boolean',
        'account_activated_at' => 'datetime',
        'badges' => 'array',
        'profile_completion' => 'integer',
    ];

    /**
     * Mutator untuk enkripsi password
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        // Only hash if not already hashed
        if (!str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Relasi dengan model Participant (peserta lomba)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function participant()
    {
        return $this->hasOne(Participant::class);
    }

    /**
     * Relasi dengan model Registration (pendaftaran lomba)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Relasi dengan model Score (penilaian juri)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scores()
    {
        return $this->hasMany(Score::class, 'jury_id');
    }

    /**
     * Relasi dengan model Judging (penilaian juri baru)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function judgings()
    {
        return $this->hasMany(Judging::class, 'jury_id');
    }

    /**
     * Relasi many-to-many dengan Competition (sebagai juri)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function juryCompetitions()
    {
        return $this->belongsToMany(Competition::class, 'competition_juries', 'user_id', 'competition_id')
                    ->withTimestamps();
    }

    /**
     * Relasi dengan model Submission melalui Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function submissions()
    {
        return $this->hasManyThrough(
            Submission::class,
            Registration::class,
            'user_id',        // Foreign key on registrations table
            'registration_id', // Foreign key on submissions table
            'id',             // Local key on users table
            'id'              // Local key on registrations table
        );
    }

    /**
     * Relasi dengan model Payment melalui Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,
            Registration::class,
            'user_id',        // Foreign key on registrations table
            'registration_id', // Foreign key on payments table
            'id',             // Local key on users table
            'id'              // Local key on registrations table
        );
    }

    /**
     * Relasi dengan NotificationPreference
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function notificationPreference()
    {
        return $this->hasOne(\App\Models\NotificationPreference::class);
    }

    /**
     * Scope untuk mendapatkan user yang aktif
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mendapatkan user berdasarkan role
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRole($query, $role)
    {
        return $query->role($role);
    }

    /**
     * Accessor untuk mendapatkan URL avatar
     * 
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }
        
        // Generate avatar default berdasarkan initial nama
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&color=ffffff&background=007bff&size=100";
    }

    /**
     * Cek apakah user adalah Super Admin
     * 
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Cek apakah user adalah Admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Cek apakah user adalah Finance
     *
     * @return bool
     */
    public function isFinance()
    {
        return $this->hasRole('finance');
    }

    /**
     * Cek apakah user adalah Juri
     *
     * @return bool
     */
    public function isJuri()
    {
        return $this->hasRole('juri');
    }

    /**
     * Cek apakah user adalah Peserta
     *
     * @return bool
     */
    public function isPeserta()
    {
        return $this->hasRole('peserta');
    }

    /**
     * Update waktu login terakhir
     *
     * @return void
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Get activity logs for this user
     */
    public function activityLogs()
    {
        return $this->hasMany(\App\Models\ActivityLog::class);
    }

    /**
     * Get activities caused by this user
     */
    public function causedActivities()
    {
        return $this->morphMany(\App\Models\ActivityLog::class, 'causer');
    }

    /**
     * Calculate and update profile completion percentage
     *
     * @return int
     */
    public function calculateProfileCompletion(): int
    {
        $fields = [
            'name' => 10,
            'email' => 10,
            'phone' => 10,
            'avatar' => 10,
            'institution' => 10,
            'bio' => 10,
            'birth_date' => 5,
            'gender' => 5,
            'address' => 5,
            'city' => 5,
            'province' => 5,
            'student_id' => 5,
            'linkedin_url' => 2,
            'twitter_url' => 2,
            'instagram_url' => 2,
            'facebook_url' => 2,
            'github_url' => 2,
            'website_url' => 2,
        ];

        $completion = 0;
        foreach ($fields as $field => $weight) {
            if (!empty($this->$field)) {
                $completion += $weight;
            }
        }

        $this->update(['profile_completion' => $completion]);
        return $completion;
    }

    /**
     * Get profile completion percentage
     *
     * @return int
     */
    public function getProfileCompletionAttribute(): int
    {
        return $this->attributes['profile_completion'] ?? 0;
    }

    /**
     * Add a badge to user
     *
     * @param string $badge
     * @return void
     */
    public function addBadge(string $badge): void
    {
        $badges = $this->badges ?? [];

        if (!in_array($badge, $badges)) {
            $badges[] = $badge;
            $this->update(['badges' => $badges]);
        }
    }

    /**
     * Remove a badge from user
     *
     * @param string $badge
     * @return void
     */
    public function removeBadge(string $badge): void
    {
        $badges = $this->badges ?? [];
        $badges = array_diff($badges, [$badge]);
        $this->update(['badges' => array_values($badges)]);
    }

    /**
     * Check if user has a specific badge
     *
     * @param string $badge
     * @return bool
     */
    public function hasBadge(string $badge): bool
    {
        return in_array($badge, $this->badges ?? []);
    }

    /**
     * Get all available badges
     *
     * @return array
     */
    public static function getAvailableBadges(): array
    {
        return [
            'early_bird' => [
                'name' => 'Early Bird',
                'description' => 'Registered in the first week',
                'icon' => 'bi-alarm',
                'color' => 'primary',
            ],
            'winner' => [
                'name' => 'Winner',
                'description' => 'Won a competition',
                'icon' => 'bi-trophy',
                'color' => 'warning',
            ],
            'finalist' => [
                'name' => 'Finalist',
                'description' => 'Reached finals',
                'icon' => 'bi-award',
                'color' => 'success',
            ],
            'participant' => [
                'name' => 'Participant',
                'description' => 'Participated in a competition',
                'icon' => 'bi-person-check',
                'color' => 'info',
            ],
            'top_scorer' => [
                'name' => 'Top Scorer',
                'description' => 'Achieved highest score',
                'icon' => 'bi-star-fill',
                'color' => 'danger',
            ],
            'profile_complete' => [
                'name' => 'Profile Master',
                'description' => 'Completed 100% of profile',
                'icon' => 'bi-check-circle-fill',
                'color' => 'success',
            ],
        ];
    }

    /**
     * Get social media links
     *
     * @return array
     */
    public function getSocialMediaLinks(): array
    {
        return [
            'linkedin' => $this->linkedin_url,
            'twitter' => $this->twitter_url,
            'instagram' => $this->instagram_url,
            'facebook' => $this->facebook_url,
            'github' => $this->github_url,
            'website' => $this->website_url,
        ];
    }

    /**
     * Check if user has any social media links
     *
     * @return bool
     */
    public function hasSocialMediaLinks(): bool
    {
        return !empty($this->linkedin_url) ||
               !empty($this->twitter_url) ||
               !empty($this->instagram_url) ||
               !empty($this->facebook_url) ||
               !empty($this->github_url) ||
               !empty($this->website_url);
    }
}
