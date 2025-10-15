<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Activity Log Model
 * 
 * Tracks all user activities and system events
 */
class ActivityLog extends Model
{
    use HasFactory;

    const UPDATED_AT = null; // Only use created_at

    protected $fillable = [
        'user_id',
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'event',
        'causer_type',
        'causer_id',
        'properties',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who owns this log
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject (polymorphic)
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the causer (polymorphic)
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope: Filter by log name
     */
    public function scopeLogName($query, $logName)
    {
        return $query->where('log_name', $logName);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by causer
     */
    public function scopeCausedBy($query, $causerId)
    {
        return $query->where('causer_id', $causerId);
    }

    /**
     * Scope: Filter by subject
     */
    public function scopeForSubject($query, $subjectType, $subjectId = null)
    {
        $query->where('subject_type', $subjectType);
        
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        
        return $query;
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate = null)
    {
        $query->whereDate('created_at', '>=', $startDate);
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        return $query;
    }

    /**
     * Scope: Recent logs
     */
    public function scopeRecent($query, $limit = 50)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Get formatted description with subject link
     */
    public function getFormattedDescriptionAttribute()
    {
        return $this->description;
    }

    /**
     * Get icon based on log name
     */
    public function getIconAttribute()
    {
        return match($this->log_name) {
            'auth' => 'bi-shield-lock',
            'registration' => 'bi-clipboard-check',
            'payment' => 'bi-cash-coin',
            'submission' => 'bi-file-earmark-text',
            'scoring' => 'bi-star',
            'admin' => 'bi-gear',
            'debate' => 'bi-chat-square-text',
            'certificate' => 'bi-award',
            'export' => 'bi-download',
            default => 'bi-activity',
        };
    }

    /**
     * Get color based on log name
     */
    public function getColorAttribute()
    {
        return match($this->log_name) {
            'auth' => 'primary',
            'registration' => 'success',
            'payment' => 'warning',
            'submission' => 'info',
            'scoring' => 'danger',
            'admin' => 'dark',
            'debate' => 'purple',
            'certificate' => 'gold',
            'export' => 'secondary',
            default => 'secondary',
        };
    }
}

