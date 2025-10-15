<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamStanding extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'matches_played',
        'team_points',
        'speaker_points',
        'average_speaker_points',
        'avg_position',
        'first_places',
        'second_places',
        'third_places',
        'fourth_places',
        'prelim_team_points',
        'prelim_speaker_points',
        'prelim_avg_position',
        'semifinal_team_points',
        'semifinal_speaker_points',
        'semifinal_avg_position',
        'final_team_points',
        'final_speaker_points',
        'final_avg_position',
    ];

    protected $casts = [
        'matches_played' => 'integer',
        'team_points' => 'integer',
        'speaker_points' => 'decimal:2',
        'average_speaker_points' => 'decimal:2',
        'avg_position' => 'decimal:2',
        'first_places' => 'integer',
        'second_places' => 'integer',
        'third_places' => 'integer',
        'fourth_places' => 'integer',
        'prelim_team_points' => 'integer',
        'prelim_speaker_points' => 'decimal:2',
        'prelim_avg_position' => 'decimal:2',
        'semifinal_team_points' => 'integer',
        'semifinal_speaker_points' => 'decimal:2',
        'semifinal_avg_position' => 'decimal:2',
        'final_team_points' => 'integer',
        'final_speaker_points' => 'decimal:2',
        'final_avg_position' => 'decimal:2',
    ];

    // Relationships
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    // Scopes
    public function scopeOrderByTeamPoints($query, string $direction = 'desc')
    {
        return $query->orderBy('team_points', $direction)
                     ->orderBy('speaker_points', $direction)
                     ->orderBy('avg_position', 'asc');
    }

    public function scopeOrderBySpeakerPoints($query, string $direction = 'desc')
    {
        return $query->orderBy('speaker_points', $direction)
                     ->orderBy('team_points', $direction)
                     ->orderBy('avg_position', 'asc');
    }

    // Helper methods
    public function updateStandings(int $teamPoints, float $speakerPoints, int $position, string $stage = 'PRELIMINARY'): void
    {
        $this->increment('matches_played');
        $this->increment('team_points', $teamPoints);
        $this->increment('speaker_points', $speakerPoints);

        // Update position counts
        match ($position) {
            1 => $this->increment('first_places'),
            2 => $this->increment('second_places'),
            3 => $this->increment('third_places'),
            4 => $this->increment('fourth_places'),
            default => null,
        };

        // Update stage-specific stats
        $stagePrefix = strtolower($stage);
        if (in_array($stage, ['PRELIMINARY', 'SEMIFINAL', 'FINAL'])) {
            $this->increment("{$stagePrefix}_team_points", $teamPoints);
            $this->increment("{$stagePrefix}_speaker_points", $speakerPoints);
        }

        // Recalculate averages
        $this->average_speaker_points = $this->matches_played > 0
            ? $this->speaker_points / $this->matches_played
            : 0;

        $totalPositions = ($this->first_places * 1) +
                         ($this->second_places * 2) +
                         ($this->third_places * 3) +
                         ($this->fourth_places * 4);
        $this->avg_position = $this->matches_played > 0
            ? $totalPositions / $this->matches_played
            : 0;

        $this->save();
    }
}
