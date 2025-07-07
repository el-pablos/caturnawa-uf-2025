<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RoundMatch untuk mengelola pertandingan dalam babak
 *
 * Menangani pertandingan individual dalam setiap babak kompetisi
 */
class RoundMatch extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'competition_round_id',
        'match_name',
        'room_name',
        'motion',
        'scheduled_at',
        'status',
        'settings',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'settings' => 'array',
    ];

    /**
     * Konstanta untuk status pertandingan
     */
    const STATUSES = [
        'scheduled' => 'Terjadwal',
        'ongoing' => 'Sedang Berlangsung',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    /**
     * Relasi dengan model CompetitionRound
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competitionRound()
    {
        return $this->belongsTo(CompetitionRound::class);
    }

    /**
     * Relasi dengan model TeamMatchup
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamMatchups()
    {
        return $this->hasMany(TeamMatchup::class);
    }

    /**
     * Scope untuk filter berdasarkan status
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
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

    /**
     * Cek apakah pertandingan sudah selesai
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Cek apakah pertandingan sedang berlangsung
     *
     * @return bool
     */
    public function isOngoing()
    {
        return $this->status === 'ongoing';
    }
}
