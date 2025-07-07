<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model TeamMatchup untuk mengelola penjadwalan tim dalam pertandingan
 *
 * Menangani assignment tim ke posisi debat dan penilaian
 */
class TeamMatchup extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'round_match_id',
        'registration_id',
        'position',
        'jury_id',
        'team_score',
        'victory_points',
        'ranking',
        'individual_scores',
        'feedback',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'team_score' => 'decimal:2',
        'victory_points' => 'integer',
        'ranking' => 'integer',
        'individual_scores' => 'array',
    ];

    /**
     * Konstanta untuk posisi debat
     */
    const POSITIONS = [
        'OG' => 'Opening Government',
        'OO' => 'Opening Opposition',
        'CG' => 'Closing Government',
        'CO' => 'Closing Opposition',
    ];

    /**
     * Relasi dengan model RoundMatch
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roundMatch()
    {
        return $this->belongsTo(RoundMatch::class);
    }

    /**
     * Relasi dengan model Registration (tim)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Relasi dengan model User (juri)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jury()
    {
        return $this->belongsTo(User::class, 'jury_id');
    }

    /**
     * Scope untuk filter berdasarkan posisi
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $position
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Scope untuk urutkan berdasarkan victory points
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByVictoryPoints($query)
    {
        return $query->orderBy('victory_points', 'desc');
    }

    /**
     * Accessor untuk mendapatkan nama posisi
     *
     * @return string
     */
    public function getPositionNameAttribute()
    {
        return self::POSITIONS[$this->position] ?? $this->position;
    }

    /**
     * Cek apakah sudah dinilai
     *
     * @return bool
     */
    public function isScored()
    {
        return !is_null($this->team_score);
    }
}
