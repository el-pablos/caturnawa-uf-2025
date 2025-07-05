<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Judging extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'jury_id',
        'score',
        'feedback',
        'judged_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'judged_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function jury()
    {
        return $this->belongsTo(User::class, 'jury_id');
    }

    /**
     * Scopes
     */
    public function scopeByJury($query, $juryId)
    {
        return $query->where('jury_id', $juryId);
    }

    public function scopeBySubmission($query, $submissionId)
    {
        return $query->where('submission_id', $submissionId);
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('judged_at');
    }

    /**
     * Accessors
     */
    public function getFormattedScoreAttribute()
    {
        return number_format($this->score, 2);
    }

    /**
     * Check if judging is complete
     */
    public function isComplete()
    {
        return !is_null($this->judged_at) && !is_null($this->score);
    }

    /**
     * Mark judging as complete
     */
    public function markAsComplete()
    {
        $this->judged_at = now();
        return $this->save();
    }
}
