<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'registration_id',
        'team_name',
        'participant_name',
        'institution',
        'score',
        'victory_points',
        'rank',
        'rank_type',
        'is_active',
        'computed_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'computed_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCompetition($query, $competitionId)
    {
        return $query->where('competition_id', $competitionId);
    }

    public function scopeTopRanks($query, $limit = 10)
    {
        return $query->orderBy('rank')->limit($limit);
    }

    /**
     * Get leaderboard for specific competition
     */
    public static function getLeaderboard($competitionId = null, $limit = 10)
    {
        $query = static::active()
            ->with(['competition', 'registration.user'])
            ->orderBy('rank');

        if ($competitionId) {
            $query->byCompetition($competitionId);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Update leaderboard for competition
     */
    public static function updateLeaderboard($competitionId)
    {
        // Get submissions with final scores for this competition
        $submissions = Submission::with(['registration.user', 'scores'])
            ->whereHas('registration', function ($q) use ($competitionId) {
                $q->where('status', 'confirmed')
                  ->where('competition_id', $competitionId);
            })
            ->where('status', 'submitted')
            ->get();

        // Clear existing entries for this competition
        static::where('competition_id', $competitionId)->delete();

        $entries = [];
        foreach ($submissions as $submission) {
            // Calculate average score from final scores only
            $finalScores = $submission->scores->where('is_final', true);

            if ($finalScores->count() === 0) {
                continue; // Skip submissions without final scores
            }

            $averageScore = $finalScores->avg('total_score');
            $victoryPoints = round($averageScore * 10);

            $entries[] = [
                'competition_id' => $competitionId,
                'registration_id' => $submission->registration_id,
                'team_name' => $submission->registration->team_name ?: $submission->registration->user->name,
                'participant_name' => $submission->registration->user->name,
                'institution' => $submission->registration->user->institution,
                'score' => round($averageScore, 2),
                'victory_points' => $victoryPoints,
                'rank' => 0, // Will be set after sorting
                'rank_type' => 'position',
                'is_active' => true,
                'computed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Sort by victory points and assign ranks
        usort($entries, function ($a, $b) {
            return $b['victory_points'] <=> $a['victory_points'];
        });

        foreach ($entries as $index => &$entry) {
            if ($index < 3) {
                $entry['rank'] = $index + 1;
                $entry['rank_type'] = 'position';
            } else if ($index == 3) {
                $entry['rank'] = 4;
                $entry['rank_type'] = 'mention';
            } else {
                $entry['rank'] = $index + 1;
                $entry['rank_type'] = 'position';
            }
        }

        // Insert entries
        if (!empty($entries)) {
            static::insert($entries);
        }
    }
}
