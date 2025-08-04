<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model TeamMatchup untuk mengelola penjadwalan tim dalam debate rounds
 * 
 * Menangani sistem pairing tim, scoring, dan ranking dalam format debate
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
        'team_position',
        'room_number',
        'zoom_meeting_id',
        'zoom_meeting_url',
        'adjudicator_id',
        'individual_scores',
        'team_score',
        'ranking',
        'victory_points',
        'notes',
        'motion',
        'is_active',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'individual_scores' => 'array',
        'team_score' => 'decimal:2',
        'victory_points' => 'integer',
        'ranking' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Team positions dalam debate
     */
    const TEAM_POSITIONS = [
        'government' => 'Government',
        'opposition' => 'Opposition',
        'team_1' => 'Team 1',
        'team_2' => 'Team 2',
        'team_3' => 'Team 3',
        'team_4' => 'Team 4',
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
     * Relasi dengan model User (adjudicator/juri)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adjudicator()
    {
        return $this->belongsTo(User::class, 'adjudicator_id');
    }

    /**
     * Scope untuk filter berdasarkan round match
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $roundMatchId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRoundMatch($query, $roundMatchId)
    {
        return $query->where('round_match_id', $roundMatchId);
    }

    /**
     * Scope untuk tim yang aktif
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get team name dari registration
     *
     * @return string
     */
    public function getTeamNameAttribute()
    {
        return $this->registration ? $this->registration->team_name : 'Unknown Team';
    }

    /**
     * Get position name yang readable
     *
     * @return string
     */
    public function getPositionNameAttribute()
    {
        return self::TEAM_POSITIONS[$this->team_position] ?? $this->team_position;
    }

    /**
     * Calculate total score dari individual scores
     *
     * @return float
     */
    public function calculateTotalScore()
    {
        if (!$this->individual_scores || !is_array($this->individual_scores)) {
            return 0;
        }

        $total = 0;
        $count = 0;
        
        foreach ($this->individual_scores as $criteria => $score) {
            if (is_numeric($score) && $score > 0) {
                $total += (float) $score;
                $count++;
            }
        }

        return $count > 0 ? ($total / $count) : 0;
    }

    /**
     * Update team score berdasarkan individual scores
     *
     * @return void
     */
    public function updateTeamScore()
    {
        $this->team_score = $this->calculateTotalScore();
        $this->save();
    }

    /**
     * Set victory points berdasarkan ranking
     *
     * @param int $rank
     * @return void
     */
    public function setVictoryPoints($rank)
    {
        $victoryPoints = \App\Models\Score::getVictoryPointSystem();
        $this->ranking = $rank;
        $this->victory_points = $victoryPoints[$rank] ?? 0;
        $this->save();
    }

    /**
     * Generate Zoom meeting URL untuk room
     *
     * @return string|null
     */
    public function generateZoomMeetingUrl()
    {
        // Placeholder untuk Zoom integration
        // Implementasi actual memerlukan Zoom API
        if ($this->zoom_meeting_id) {
            return "https://zoom.us/j/{$this->zoom_meeting_id}";
        }
        
        return null;
    }

    /**
     * Check if team sudah mendapat score
     *
     * @return bool
     */
    public function hasScore()
    {
        return !is_null($this->team_score) && $this->team_score > 0;
    }

    /**
     * Get EDC grade berdasarkan team score
     *
     * @return string
     */
    public function getEdcGrade()
    {
        $score = $this->team_score;
        
        if ($score >= 96) return 'A+';
        if ($score >= 91) return 'A';
        if ($score >= 86) return 'A-';
        if ($score >= 81) return 'B+';
        if ($score >= 76) return 'B';
        if ($score >= 71) return 'B-';
        if ($score >= 66) return 'C+';
        if ($score >= 61) return 'C';
        if ($score >= 56) return 'C-';
        if ($score >= 50) return 'D';
        
        return 'F';
    }

    /**
     * Format individual scores untuk display
     *
     * @return array
     */
    public function getFormattedScores()
    {
        if (!$this->individual_scores || !is_array($this->individual_scores)) {
            return [];
        }

        $edcCriteria = \App\Models\Score::getEdcCriteria();
        $formatted = [];

        foreach ($this->individual_scores as $criteria => $score) {
            $formatted[] = [
                'criteria' => $edcCriteria[$criteria] ?? $criteria,
                'score' => $score,
                'level' => \App\Models\Score::getEdcScoringLevel($score)
            ];
        }

        return $formatted;
    }
}