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
 * dengan data peserta lomba UNAS Fest 2025
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
        'is_active',
        'email_verified_at',
        'last_login_at',
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
    ];

    /**
     * Mutator untuk enkripsi password
     * 
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
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
        return $this->hasRole('Super Admin');
    }

    /**
     * Cek apakah user adalah Admin
     * 
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }

    /**
     * Cek apakah user adalah Juri
     * 
     * @return bool
     */
    public function isJuri()
    {
        return $this->hasRole('Juri');
    }

    /**
     * Cek apakah user adalah Peserta
     * 
     * @return bool
     */
    public function isPeserta()
    {
        return $this->hasRole('Peserta');
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
}
