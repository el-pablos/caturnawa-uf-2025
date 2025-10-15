<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAndCondition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'title_en',
        'title_id',
        'content',
        'content_en',
        'content_id',
        'type',
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
     * Terms and conditions types
     */
    const TYPE_GENERAL = 'general';
    const TYPE_COMPETITION = 'competition';
    const TYPE_PRIVACY = 'privacy';
    const TYPE_PAYMENT = 'payment';

    /**
     * Scope a query to only include active terms and conditions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include terms and conditions of a specific type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
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

    /**
     * Get the content in the current locale
     */
    public function getLocalizedContentAttribute()
    {
        $locale = app()->getLocale();
        $column = 'content_' . $locale;

        return $this->{$column} ?? $this->content;
    }
}

