<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model CompetitionRound untuk mengelola babak kompetisi
 *
 * Menangani babak-babak kompetisi seperti penyisihan, semifinal, final
 */
class CompetitionRound extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'competition_id',
        'round_type',
        'name',
        'description',
        'round_number',
        'start_date',
        'end_date',
        'status',
        'is_active',
        'settings',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Konstanta untuk tipe babak
     */
    const ROUND_TYPES = [
        'penyisihan' => 'Babak Penyisihan',
        'semifinal' => 'Semifinal',
        'final' => 'Final',
    ];

    /**
     * Konstanta untuk status babak
     */
    const STATUSES = [
        'upcoming' => 'Akan Datang',
        'ongoing' => 'Sedang Berlangsung',
        'completed' => 'Selesai',
    ];

    /**
     * Relasi dengan model Competition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Relasi dengan model RoundMatch
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matches()
    {
        return $this->hasMany(RoundMatch::class);
    }

    /**
     * Scope untuk filter berdasarkan tipe babak
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('round_type', $type);
    }

    /**
     * Scope untuk filter babak aktif
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Accessor untuk mendapatkan nama tipe babak
     *
     * @return string
     */
    public function getRoundTypeNameAttribute()
    {
        return self::ROUND_TYPES[$this->round_type] ?? $this->round_type;
    }

    /**
     * Accessor untuk mendapatkan nama status
     *
     * @return string
     */
    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
