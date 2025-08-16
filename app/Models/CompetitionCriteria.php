<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionCriteria extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'competition_id',
        'criteria_name',
        'description', 
        'weight_percentage',
        'sub_criteria',
        'max_score',
        'order_index'
    ];
    
    protected $casts = [
        'sub_criteria' => 'array',
        'weight_percentage' => 'integer',
        'max_score' => 'integer', 
        'order_index' => 'integer'
    ];
    
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
}
