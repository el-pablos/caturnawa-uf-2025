<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'title',
        'description',
        'data',
        'computed_at',
    ];

    protected $casts = [
        'data' => 'array',
        'computed_at' => 'datetime',
    ];

    /**
     * Get achievement by key
     */
    public static function getByKey($key)
    {
        return static::where('key', $key)->first();
    }

    /**
     * Update or create achievement data
     */
    public static function updateData($key, $title, $data, $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'title' => $title,
                'description' => $description,
                'data' => $data,
                'computed_at' => now(),
            ]
        );
    }
}
