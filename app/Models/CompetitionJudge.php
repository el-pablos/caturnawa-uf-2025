<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionJudge extends Model
{
    use HasFactory;

    protected $table = 'competition_juries';

    protected $fillable = [
        'competition_id',
        'user_id',
        'judge_name',
        'title',
        'expertise',
        'bio',
        'photo',
        'is_active',
        'order_index'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer'
    ];
    
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
