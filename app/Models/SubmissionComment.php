<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model untuk komentar submission dari juri
 * 
 * @property int $id
 * @property int $submission_id
 * @property int $jury_id
 * @property string $comment
 * @property int|null $rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class SubmissionComment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'submission_id',
        'jury_id',
        'comment',
        'rating',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the submission that owns the comment.
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get the jury that wrote the comment.
     */
    public function jury()
    {
        return $this->belongsTo(User::class, 'jury_id');
    }

    /**
     * Scope untuk filter berdasarkan rating
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope untuk filter berdasarkan juri
     */
    public function scopeByJury($query, $juryId)
    {
        return $query->where('jury_id', $juryId);
    }

    /**
     * Get rating text
     */
    public function getRatingTextAttribute()
    {
        if (!$this->rating) {
            return 'Tidak ada rating';
        }

        $ratings = [
            1 => 'Sangat Kurang',
            2 => 'Kurang',
            3 => 'Cukup',
            4 => 'Baik',
            5 => 'Sangat Baik',
        ];

        return $ratings[$this->rating] ?? 'Unknown';
    }

    /**
     * Get rating stars HTML
     */
    public function getRatingStarsAttribute()
    {
        if (!$this->rating) {
            return '';
        }

        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="bi bi-star-fill text-warning"></i>';
            } else {
                $stars .= '<i class="bi bi-star text-muted"></i>';
            }
        }

        return $stars;
    }
}
