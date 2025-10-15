<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DebateMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_id',
        'match_number',
        'match_format',
        'team1_id',
        'team2_id',
        'team3_id',
        'team4_id',
        'judge_id',
        'room_name',
        'first_place_team_id',
        'second_place_team_id',
        'third_place_team_id',
        'fourth_place_team_id',
        'scheduled_at',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function round(): BelongsTo
    {
        return $this->belongsTo(DebateRound::class, 'round_id');
    }

    public function team1(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'team1_id');
    }

    public function team2(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'team2_id');
    }

    public function team3(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'team3_id');
    }

    public function team4(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'team4_id');
    }

    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(DebateScore::class, 'match_id');
    }

    public function firstPlaceTeam(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'first_place_team_id');
    }

    public function secondPlaceTeam(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'second_place_team_id');
    }

    public function thirdPlaceTeam(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'third_place_team_id');
    }

    public function fourthPlaceTeam(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'fourth_place_team_id');
    }

    // Scopes
    public function scopeForRound($query, int $roundId)
    {
        return $query->where('round_id', $roundId);
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('completed_at');
    }

    public function scopeForJudge($query, int $judgeId)
    {
        return $query->where('judge_id', $judgeId);
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function markAsCompleted(): void
    {
        $this->update(['completed_at' => now()]);
    }

    public function getTeams(): array
    {
        return array_filter([
            $this->team1,
            $this->team2,
            $this->team3,
            $this->team4,
        ]);
    }

    public function hasAllTeams(): bool
    {
        return $this->team1_id && $this->team2_id && $this->team3_id && $this->team4_id;
    }
}
