<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DebateScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'team_member_id',
        'judge_id',
        'score',
        'bp_position',
        'team_position',
        'speaker_rank',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'speaker_rank' => 'integer',
    ];

    // Relationships
    public function match(): BelongsTo
    {
        return $this->belongsTo(DebateMatch::class, 'match_id');
    }

    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'team_member_id');
    }

    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    // Scopes
    public function scopeForMatch($query, int $matchId)
    {
        return $query->where('match_id', $matchId);
    }

    public function scopeForJudge($query, int $judgeId)
    {
        return $query->where('judge_id', $judgeId);
    }

    public function scopeByBpPosition($query, string $position)
    {
        return $query->where('bp_position', $position);
    }

    // Helper methods
    public function isValidScore(): bool
    {
        return $this->score >= 70 && $this->score <= 80;
    }
}
