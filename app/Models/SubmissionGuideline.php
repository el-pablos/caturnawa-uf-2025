<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionGuideline extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'title',
        'content',
        'type',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Guideline types
     */
    const TYPES = [
        'requirement' => 'Persyaratan',
        'format' => 'Format Submission',
        'evaluation' => 'Kriteria Penilaian',
        'deadline' => 'Deadline',
        'general' => 'Umum',
    ];

    /**
     * Relationships
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }

    /**
     * Accessors
     */
    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Get guidelines by competition and type
     */
    public static function getByCompetitionAndType($competitionId, $type = null)
    {
        $query = static::where('competition_id', $competitionId)->active()->ordered();

        if ($type) {
            $query->byType($type);
        }

        return $query->get();
    }

    /**
     * Get all guidelines grouped by type for a competition
     */
    public static function getGroupedByCompetition($competitionId)
    {
        return static::where('competition_id', $competitionId)
            ->active()
            ->ordered()
            ->get()
            ->groupBy('type');
    }
}
