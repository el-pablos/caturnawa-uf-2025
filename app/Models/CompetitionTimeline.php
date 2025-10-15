<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionTimeline extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'competition_id',
        'month',
        'day',
        'year',
        'title',
        'title_en',
        'title_id',
        'order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the competition that owns the timeline.
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Scope a query to only include active timeline events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include timeline events for a specific competition.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $competitionId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCompetition($query, $competitionId)
    {
        return $query->where('competition_id', $competitionId);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('ordered', function ($query) {
            $query->orderBy('order', 'asc');
        });
    }

    /**
     * Get the title in the current locale
     */
    public function getLocalizedTitleAttribute()
    {
        $locale = app()->getLocale();
        $column = 'title_' . $locale;

        return $this->{$column} ?? $this->title;
    }
}

