<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'section',
        'title',
        'content',
        'order',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }

    /**
     * Get descriptions by competition and section
     */
    public static function getByCompetitionAndSection($competitionId, $section = 'main')
    {
        return static::where('competition_id', $competitionId)
            ->bySection($section)
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get all sections for a competition
     */
    public static function getSectionsByCompetition($competitionId)
    {
        return static::where('competition_id', $competitionId)
            ->active()
            ->select('section')
            ->distinct()
            ->pluck('section');
    }
}
