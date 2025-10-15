<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionRequirement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'competition_id',
        'field_name',
        'field_type',
        'field_label',
        'help_text',
        'is_required',
        'validation_rules',
        'field_options',
        'field_group',
        'order_index'
    ];
    
    protected $casts = [
        'is_required' => 'boolean',
        'validation_rules' => 'array',
        'field_options' => 'array',
        'order_index' => 'integer'
    ];
    
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }
}
