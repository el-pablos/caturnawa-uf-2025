<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DebateRound extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'stage',
        'round_number',
        'session',
        'round_name',
        'motion',
        'is_frozen',
        'frozen_at',
        'frozen_by',
    ];

    protected $casts = [
        'is_frozen' => 'boolean',
        'frozen_at' => 'datetime',
    ];

    // Relationships
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(DebateMatch::class, 'round_id');
    }

    // Scopes
    public function scopeByStage($query, string $stage)
    {
        return $query->where('stage', $stage);
    }

    public function scopeFrozen($query)
    {
        return $query->where('is_frozen', true);
    }

    public function scopeNotFrozen($query)
    {
        return $query->where('is_frozen', false);
    }

    public function scopeForCompetition($query, int $competitionId)
    {
        return $query->where('competition_id', $competitionId);
    }

    // Helper methods
    public function freeze(string $userId): void
    {
        $this->update([
            'is_frozen' => true,
            'frozen_at' => now(),
            'frozen_by' => $userId,
        ]);
    }

    public function unfreeze(): void
    {
        $this->update([
            'is_frozen' => false,
            'frozen_at' => null,
            'frozen_by' => null,
        ]);
    }

    public function isFrozen(): bool
    {
        return $this->is_frozen;
    }
}
