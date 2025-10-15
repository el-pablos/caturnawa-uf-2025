<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question',
        'question_en',
        'question_id',
        'answer',
        'answer_en',
        'answer_id',
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
     * Scope a query to only include active FAQs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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
     * Get the question in the current locale
     */
    public function getLocalizedQuestionAttribute()
    {
        $locale = app()->getLocale();
        $column = 'question_' . $locale;

        return $this->{$column} ?? $this->question;
    }

    /**
     * Get the answer in the current locale
     */
    public function getLocalizedAnswerAttribute()
    {
        $locale = app()->getLocale();
        $column = 'answer_' . $locale;

        return $this->{$column} ?? $this->answer;
    }
}

