<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Score untuk mengelola penilaian juri
 * 
 * Kelas ini menangani sistem penilaian kompetisi
 * oleh juri dengan berbagai kriteria
 */
class Score extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'competition_id',
        'registration_id',
        'jury_id',
        'criteria_scores',
        'total_score',
        'comments',
        'is_final',
        'submitted_at',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'criteria_scores' => 'array',
        'total_score' => 'decimal:2',
        'is_final' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    /**
     * Relasi dengan model Competition
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Relasi dengan model Registration (peserta)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Relasi dengan model User (juri)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jury()
    {
        return $this->belongsTo(User::class, 'jury_id');
    }

    /**
     * Boot method untuk kalkulasi otomatis total score
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($score) {
            if ($score->criteria_scores) {
                $score->total_score = $score->calculateTotalScore();
            }
        });
    }

    /**
     * Kalkulasi total score dari criteria scores
     * 
     * @return float
     */
    protected function calculateTotalScore()
    {
        if (!$this->criteria_scores || !is_array($this->criteria_scores)) {
            return 0;
        }

        $total = 0;
        $criteriaCount = 0;

        foreach ($this->criteria_scores as $criteria => $score) {
            if (is_numeric($score) && $score > 0) {
                $total += floatval($score);
                $criteriaCount++;
            }
        }

        return $criteriaCount > 0 ? ($total / $criteriaCount) : 0;
    }

    /**
     * Scope untuk penilaian yang sudah final
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFinal($query)
    {
        return $query->where('is_final', true);
    }

    /**
     * Scope untuk penilaian draft
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('is_final', false);
    }

    /**
     * Scope berdasarkan kompetisi
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $competitionId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCompetition($query, $competitionId)
    {
        return $query->where('competition_id', $competitionId);
    }

    /**
     * Scope berdasarkan juri
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $juryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByJury($query, $juryId)
    {
        return $query->where('jury_id', $juryId);
    }

    /**
     * Submit penilaian sebagai final
     * 
     * @return void
     */
    public function submitFinal()
    {
        $this->update([
            'is_final' => true,
            'submitted_at' => now(),
        ]);
    }

    /**
     * Cek apakah penilaian sudah final
     * 
     * @return bool
     */
    public function isFinal()
    {
        return $this->is_final;
    }

    /**
     * Cek apakah penilaian masih draft
     * 
     * @return bool
     */
    public function isDraft()
    {
        return !$this->is_final;
    }

    /**
     * Accessor untuk mendapatkan rata-rata score
     * 
     * @return float
     */
    public function getAverageScoreAttribute()
    {
        return $this->total_score;
    }

    /**
     * Accessor untuk mendapatkan grade berdasarkan score
     * 
     * @return string
     */
    public function getGradeAttribute()
    {
        $score = $this->total_score;
        
        if ($score >= 90) return 'A+';
        if ($score >= 85) return 'A';
        if ($score >= 80) return 'A-';
        if ($score >= 75) return 'B+';
        if ($score >= 70) return 'B';
        if ($score >= 65) return 'B-';
        if ($score >= 60) return 'C+';
        if ($score >= 55) return 'C';
        if ($score >= 50) return 'C-';
        
        return 'D';
    }

    /**
     * Mendapatkan skor untuk kriteria tertentu
     * 
     * @param string $criteria
     * @return float|null
     */
    public function getCriteriaScore($criteria)
    {
        return $this->criteria_scores[$criteria] ?? null;
    }

    /**
     * Set skor untuk kriteria tertentu
     * 
     * @param string $criteria
     * @param float $score
     * @return void
     */
    public function setCriteriaScore($criteria, $score)
    {
        $scores = $this->criteria_scores ?? [];
        $scores[$criteria] = $score;
        $this->criteria_scores = $scores;
        $this->save();
    }

    /**
     * Mendapatkan semua kriteria yang tersedia
     * 
     * @return array
     */
    public static function getDefaultCriteria()
    {
        return [
            'innovation' => 'Inovasi',
            'creativity' => 'Kreativitas', 
            'technical' => 'Aspek Teknis',
            'presentation' => 'Presentasi',
            'impact' => 'Dampak/Manfaat',
            'feasibility' => 'Kelayakan',
        ];
    }

    /**
     * Validasi apakah semua kriteria sudah dinilai
     * 
     * @return bool
     */
    public function isComplete()
    {
        if (!$this->criteria_scores) {
            return false;
        }

        $requiredCriteria = self::getDefaultCriteria();
        
        foreach (array_keys($requiredCriteria) as $criteria) {
            if (!isset($this->criteria_scores[$criteria]) || 
                !is_numeric($this->criteria_scores[$criteria]) ||
                $this->criteria_scores[$criteria] <= 0) {
                return false;
            }
        }

        return true;
    }
}
