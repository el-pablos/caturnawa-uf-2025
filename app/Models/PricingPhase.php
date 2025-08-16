<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Model PricingPhase untuk mengelola sistem pricing multi-fase
 *
 * Mendukung 4 fase pricing dengan 3 kategori peserta:
 * - Early Bird, Phase 1, Phase 2, Phase 3
 * - UNAS students, External students, High school students
 */
class PricingPhase extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phase_name',
        'participant_category',
        'amount',
        'start_date',
        'end_date',
        'is_active',
        'description',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Konstanta untuk kategori peserta
     */
    const PARTICIPANT_CATEGORIES = [
        'unas_student' => 'Mahasiswa UNAS',
        'external_student' => 'Mahasiswa Eksternal',
        'high_school_student' => 'Siswa SMA/SMK',
    ];

    /**
     * Konstanta untuk nama fase
     */
    const PHASE_NAMES = [
        'early_bird' => 'Early Bird',
        'phase_1' => 'Phase 1',
        'phase_2' => 'Phase 2',
        'phase_3' => 'Phase 3',
    ];

    /**
     * Scope untuk fase yang aktif
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk fase yang berlaku saat ini
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrent($query)
    {
        $now = now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now)
                    ->where('is_active', true);
    }

    /**
     * Scope untuk kategori peserta tertentu
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCategory($query, $category)
    {
        return $query->where('participant_category', $category);
    }

    /**
     * Dapatkan harga untuk kategori peserta pada waktu tertentu
     *
     * @param string $category
     * @param \Carbon\Carbon|null $date
     * @return float|null
     */
    public static function getPriceForCategory($category, $date = null)
    {
        $date = $date ?? now();

        $phase = static::active()
                      ->current()
                      ->forCategory($category)
                      ->first();

        return $phase ? $phase->amount : null;
    }

    /**
     * Dapatkan fase yang berlaku saat ini untuk kategori tertentu
     *
     * @param string $category
     * @param \Carbon\Carbon|null $date
     * @return \App\Models\PricingPhase|null
     */
    public static function getCurrentPhaseForCategory($category, $date = null)
    {
        $date = $date ?? now();

        return static::active()
                     ->current()
                     ->forCategory($category)
                     ->first();
    }

    /**
     * Cek apakah fase masih berlaku
     *
     * @return bool
     */
    public function isCurrentlyActive()
    {
        $now = now();
        return $this->is_active &&
               $now >= $this->start_date &&
               $now <= $this->end_date;
    }

    /**
     * Dapatkan nama kategori peserta yang mudah dibaca
     *
     * @return string
     */
    public function getCategoryNameAttribute()
    {
        return self::PARTICIPANT_CATEGORIES[$this->participant_category] ?? $this->participant_category;
    }

    /**
     * Dapatkan nama fase yang mudah dibaca
     *
     * @return string
     */
    public function getPhaseDisplayNameAttribute()
    {
        return self::PHASE_NAMES[$this->phase_name] ?? $this->phase_name;
    }
}
