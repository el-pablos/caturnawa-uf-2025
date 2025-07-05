<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'name',
        'email',
        'phone',
        'student_id',
        'institution',
        'role',
    ];

    /**
     * Relasi dengan model Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
