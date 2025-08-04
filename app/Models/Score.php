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
     * EDC Scoring Parameters sesuai dokumen EDC UNAS FEST 2025
     * Assessment Parameters with Appropriateness to Theme, Evidence Based and Interest (Novelty)
     * 
     * @return array
     */
    public static function getEdcScoringLevels()
    {
        return [
            '50-55' => [
                'description' => 'The argument is not in line with the focus of the theme. The data or evidence used is not relevant or limited. The arguments outlined have not shown novelty or relevance to the dynamics of current developments.',
                'criteria' => [
                    'theme_alignment' => 'Argument tidak sesuai dengan fokus tema',
                    'evidence_quality' => 'Data/bukti tidak relevan atau terbatas',
                    'novelty' => 'Tidak menunjukkan kebaruan atau relevansi dengan dinamika perkembangan terkini'
                ]
            ],
            '56-60' => [
                'description' => 'The ideas raised support the theme, but some of the arguments are less comprehensible. The evidence presented does not strongly support the argument.',
                'criteria' => [
                    'theme_alignment' => 'Ide mendukung tema namun beberapa argumen kurang dapat dipahami',
                    'evidence_quality' => 'Bukti yang disajikan tidak kuat mendukung argumen',
                    'novelty' => 'Masih kurang dalam aspek kebaruan'
                ]
            ],
            '61-65' => [
                'description' => 'The argument begins to be organized coherently and remains based on the main theme, equipped with relevant and supporting evidence. The evidence is quite convincing and relevant, although there are still some shortcomings in supporting the argumentation in accordance with the theme.',
                'criteria' => [
                    'theme_alignment' => 'Argumen mulai terorganisir koheren dan tetap berdasar tema utama',
                    'evidence_quality' => 'Bukti cukup meyakinkan dan relevan namun masih ada kekurangan',
                    'novelty' => 'Mulai menunjukkan kebaruan dalam pendekatan'
                ]
            ],
            '66-70' => [
                'description' => 'Arguments are consistently focused and relevant to the theme. The evidence is supportive, although still at a basic level, and there is an attempt to present a fresher and more innovative argument.',
                'criteria' => [
                    'theme_alignment' => 'Argumen konsisten fokus dan relevan dengan tema',
                    'evidence_quality' => 'Bukti mendukung meski masih level dasar',
                    'novelty' => 'Ada upaya menyajikan argumen yang lebih segar dan inovatif'
                ]
            ],
            '71-75' => [
                'description' => 'The delivery remains systematic and thematically sound. There is considerable evidence, with the emergence of newer and more progressive approaches.',
                'criteria' => [
                    'theme_alignment' => 'Penyampaian tetap sistematis dan sesuai tema',
                    'evidence_quality' => 'Bukti cukup kuat dengan pendekatan yang lebih progresif',
                    'novelty' => 'Munculnya pendekatan baru yang lebih progresif'
                ]
            ],
            '76-80' => [
                'description' => 'Arguments are beginning to be sharp and in-depth in relation to the theme. There is strong, logical evidence, and is able to explain the topic in general well. Conveys ideas that are beginning to be convincing.',
                'criteria' => [
                    'theme_alignment' => 'Argumen mulai tajam dan mendalam terkait tema',
                    'evidence_quality' => 'Bukti kuat, logis, dan mampu menjelaskan topik dengan baik',
                    'novelty' => 'Menyampaikan ide yang mulai meyakinkan'
                ]
            ],
            '81-85' => [
                'description' => 'Understanding and delivery related to the theme is good in accordance with the theme. Evidence is supported by logical reasoning and creative thinking. Delivering innovative new ideas with an approach that is quite impressive.',
                'criteria' => [
                    'theme_alignment' => 'Pemahaman dan penyampaian terkait tema baik sesuai tema',
                    'evidence_quality' => 'Bukti didukung penalaran logis dan pemikiran kreatif',
                    'novelty' => 'Menyampaikan ide baru inovatif dengan pendekatan yang cukup mengesankan'
                ]
            ],
            '86-90' => [
                'description' => 'The argumentation is sharp, reflecting a deep understanding of the theme. Provides and presents strong and relevant evidence in a broad and detailed manner. The argument is presented in a creative way and reflects a rational train of thought.',
                'criteria' => [
                    'theme_alignment' => 'Argumentasi tajam mencerminkan pemahaman mendalam tema',
                    'evidence_quality' => 'Menyajikan bukti kuat dan relevan secara luas dan detail',
                    'novelty' => 'Argumen disajikan kreatif dan mencerminkan alur pikir rasional'
                ]
            ],
            '91-95' => [
                'description' => 'Argumentation is deep, systematic and rich in viewpoints. Presents very strong and in-depth evidence that strongly supports the argument. There is a clever, innovative idea with a distinctive approach.',
                'criteria' => [
                    'theme_alignment' => 'Argumentasi mendalam, sistematis dan kaya akan sudut pandang',
                    'evidence_quality' => 'Menyajikan bukti sangat kuat dan mendalam',
                    'novelty' => 'Ada ide cerdas, inovatif dengan pendekatan yang khas'
                ]
            ],
            '96-100' => [
                'description' => 'Presents arguments and ideas that reflect originality and innovative perspectives, and is able to develop them logically and progressively with consistency throughout the debate. Has arguments that are reinforced with concrete, relevant, actual and accurate evidence, and is able to respond to POIs very well. Use sentences that show confidence and credibility with a convincing speaking style, full of emotional control and able to adjust intonation, body language, and diction according to the debate theme.',
                'criteria' => [
                    'theme_alignment' => 'Menyajikan argumen dan ide yang mencerminkan orisinalitas dan perspektif inovatif',
                    'evidence_quality' => 'Argumen diperkuat bukti konkret, relevan, aktual dan akurat',
                    'novelty' => 'Mampu merespon POI dengan sangat baik, percaya diri dengan gaya berbicara meyakinkan'
                ]
            ]
        ];
    }

    /**
     * Victory Point System sesuai dokumen EDC
     * 
     * @return array
     */
    public static function getVictoryPointSystem()
    {
        return [
            1 => 3, // Rank 1 = 3 Victory Points
            2 => 2, // Rank 2 = 2 Victory Points  
            3 => 1, // Rank 3 = 1 Victory Point
            4 => 0, // Rank 4 = 0 Victory Points
        ];
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
     * Get EDC specific criteria for debate assessment
     * 
     * @return array
     */
    public static function getEdcCriteria()
    {
        return [
            'theme_alignment' => 'Appropriateness to Theme',
            'evidence_quality' => 'Evidence Based Quality',
            'novelty_interest' => 'Interest (Novelty)',
            'argumentation' => 'Argumentation Strength',
            'delivery_style' => 'Delivery & Speaking Style',
            'poi_response' => 'Point of Information Response',
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

    /**
     * Validasi score untuk EDC (range 50-100)
     * 
     * @param float $score
     * @return bool
     */
    public static function isValidEdcScore($score)
    {
        return is_numeric($score) && $score >= 50 && $score <= 100;
    }

    /**
     * Get EDC scoring level dari score
     * 
     * @param float $score
     * @return string|null
     */
    public static function getEdcScoringLevel($score)
    {
        if (!self::isValidEdcScore($score)) {
            return null;
        }

        $levels = array_keys(self::getEdcScoringLevels());
        
        foreach ($levels as $level) {
            [$min, $max] = explode('-', $level);
            if ($score >= $min && $score <= $max) {
                return $level;
            }
        }

        return null;
    }

    /**
     * Get KDBI scoring levels (50-100) dengan 10 level detail sesuai dokumen
     * 
     * @return array
     */
    public static function getKdbiScoringLevels()
    {
        return [
            '50-55' => [
                'description' => 'Argumen yang disampaikan kurang sejalan dengan fokus tema',
                'criteria' => [
                    'Data atau bukti yang digunakan tidak relevan atau masih terbatas',
                    'Argumen yang diuraikan belum menunjukkan kebaruan atau keterkaitan dengan dinamika perkembangan saat ini'
                ]
            ],
            '56-60' => [
                'description' => 'Gagasan yang diangkat mendukung tema, tapi sejumlah penyampaian argumen kurang dapat dipahami',
                'criteria' => [
                    'Bukti yang disampaikan belum mendukung argumen yang kuat'
                ]
            ],
            '61-65' => [
                'description' => 'Argumen mulai tersusun secara runtut dan tetap berpijak pada tema utama',
                'criteria' => [
                    'Dilengkapi dengan bukti-bukti yang relevan dan mendukung',
                    'Buktinya cukup meyakinkan dan relevan, walau masih ada sedikit kekurangan dalam mendukung argumentasi yang sesuai dengan tema'
                ]
            ],
            '66-70' => [
                'description' => 'Argumen cukup konsisten terfokus dan relevan dengan tema',
                'criteria' => [
                    'Buktinya sudah mendukung, meski masih pada level dasar',
                    'Mulai terlihat adanya upaya menghadirkan argumen yang lebih inovatif'
                ]
            ],
            '71-75' => [
                'description' => 'Penyampaian tetap sesuai argumen yang sistematis dan sesuai tema',
                'criteria' => [
                    'Terdapat bukti yang cukup kuat, disertai munculnya nuansa pendekatan yang lebih baru dan progresif'
                ]
            ],
            '76-80' => [
                'description' => 'Penyampaian argumen yang mulai berkembang dan mendalam terkait tema',
                'criteria' => [
                    'Terdapat bukti kuat, logis, dan mampu menjelaskan topik secara umum dengan baik',
                    'Menyampaikan ide yang mulai meyakinkan'
                ]
            ],
            '81-85' => [
                'description' => 'Pemahaman dan penyampaian materi telah sesuai dan sejalan dengan tema yang diangkat',
                'criteria' => [
                    'Bukti didukung dengan penalaran logis dan cara berpikir kreatif',
                    'Memberikan gagasan dan ide-ide baru yang inovatif dengan pendekatan yang cukup mengesankan'
                ]
            ],
            '86-90' => [
                'description' => 'Argumentasinya terstruktur dengan baik, mencerminkan pemahaman mendalam terhadap tema yang diangkat',
                'criteria' => [
                    'Memberikan dan menyampaikan bukti yang kuat dan relevan secara luas dan terperinci',
                    'Argumentasinya disampaikan dengan cara yang kreatif dan mencerminkan alur pemikiran yang rasional'
                ]
            ],
            '91-95' => [
                'description' => 'Argumentasi mendalam, sistematis, dan kaya akan sudut pandang',
                'criteria' => [
                    'Menyampaikan bukti sangat kuat dan mendalam yang mendukung argumen dengan kuat',
                    'Terdapat inovasi ide yang cerdas, terbarukan dengan pendekatan yang khas'
                ]
            ],
            '96-100' => [
                'description' => 'Memberikan argumen dan gagasan yang menunjukkan keaslian dan sudut pandang yang inovatif',
                'criteria' => [
                    'Mampu mengembangkan ide secara logis dan progresif dengan konsisten sepanjang debat',
                    'Memiliki argumen yang diperkuat dengan bukti konkret, relevan, aktual dan disampaikan dengan akurat',
                    'Mampu menanggapi POI dengan sangat baik',
                    'Menggunakan kalimat yang menunjukkan kepercayaan diri dan kredibilitas dengan gaya bicara meyakinkan',
                    'Penuh penguasaan emosi dan mampu menyesuaikan intonasi, bahasa tubuh, dan diksi sesuai dengan tema debat'
                ]
            ]
        ];
    }

    /**
     * Get KDBI criteria untuk penilaian
     * 
     * @return array
     */
    public static function getKdbiCriteria()
    {
        return [
            'kesesuaian_tema' => [
                'name' => 'Kesesuaian Terhadap Tema',
                'description' => 'Sejauh mana argumen sejalan dengan fokus tema yang diangkat',
                'weight' => 25,
                'min_score' => 50,
                'max_score' => 100
            ],
            'evidence_based' => [
                'name' => 'Evidence Based',
                'description' => 'Kualitas dan relevansi data/bukti yang digunakan untuk mendukung argumen',
                'weight' => 25,
                'min_score' => 50,
                'max_score' => 100
            ],
            'ketertarikan_novelty' => [
                'name' => 'Ketertarikan (Novelty)',
                'description' => 'Tingkat kebaruan dan inovasi dalam penyampaian argumen',
                'weight' => 25,
                'min_score' => 50,
                'max_score' => 100
            ],
            'delivery_style' => [
                'name' => 'Gaya Penyampaian',
                'description' => 'Cara penyampaian argumentasi meliputi kepercayaan diri, kredibilitas, dan penguasaan emosi',
                'weight' => 25,
                'min_score' => 50,
                'max_score' => 100
            ]
        ];
    }

    /**
     * Get SPC criteria untuk penilaian karya ilmiah
     * 
     * @return array
     */
    public static function getSpcCriteria()
    {
        return [
            'originality_innovation' => [
                'name' => 'Orisinalitas dan Inovasi',
                'description' => 'Tingkat kebaruan, kontribusi, dan inovasi dalam penelitian',
                'weight' => 30,
                'min_score' => 0,
                'max_score' => 100
            ],
            'methodology_rigor' => [
                'name' => 'Metodologi dan Ketelitian',
                'description' => 'Kualitas metode penelitian, analisis data, dan ketelitian ilmiah',
                'weight' => 25,
                'min_score' => 0,
                'max_score' => 100
            ],
            'analysis_discussion' => [
                'name' => 'Analisis dan Pembahasan',
                'description' => 'Kedalaman analisis, interpretasi hasil, dan diskusi temuan',
                'weight' => 25,
                'min_score' => 0,
                'max_score' => 100
            ],
            'writing_structure' => [
                'name' => 'Penulisan dan Struktur',
                'description' => 'Kualitas penulisan akademik, struktur, dan presentasi',
                'weight' => 20,
                'min_score' => 0,
                'max_score' => 100
            ]
        ];
    }

    /**
     * Get SPC scoring rubric (0-100 scale untuk scientific papers)
     * 
     * @return array
     */
    public static function getSpcScoringRubric()
    {
        return [
            '90-100' => [
                'grade' => 'A',
                'description' => 'Excellent - Karya ilmiah berkualitas tinggi dengan kontribusi signifikan',
                'criteria' => [
                    'Orisinalitas dan inovasi sangat tinggi dengan kontribusi baru yang signifikan',
                    'Metodologi penelitian sangat solid dan sesuai standar ilmiah tertinggi',
                    'Analisis mendalam dengan interpretasi yang komprehensif dan akurat',
                    'Penulisan sangat baik, struktur sistematis, dan presentasi sempurna'
                ]
            ],
            '80-89' => [
                'grade' => 'B+',
                'description' => 'Very Good - Karya ilmiah berkualitas baik dengan kontribusi yang jelas',
                'criteria' => [
                    'Orisinalitas tinggi dengan kontribusi yang dapat diidentifikasi',
                    'Metodologi solid dengan aplikasi yang tepat',
                    'Analisis baik dengan interpretasi yang masuk akal',
                    'Penulisan baik dengan struktur yang jelas'
                ]
            ],
            '70-79' => [
                'grade' => 'B',
                'description' => 'Good - Karya ilmiah memenuhi standar dengan beberapa kelebihan',
                'criteria' => [
                    'Orisinalitas cukup dengan beberapa aspek baru',
                    'Metodologi sesuai dengan sedikit perbaikan yang diperlukan',
                    'Analisis memadai namun bisa diperdalam',
                    'Penulisan memenuhi standar akademik'
                ]
            ],
            '60-69' => [
                'grade' => 'C+',
                'description' => 'Satisfactory - Memenuhi standar minimum dengan perbaikan diperlukan',
                'criteria' => [
                    'Orisinalitas terbatas namun masih dapat diterima',
                    'Metodologi dasar namun perlu perbaikan',
                    'Analisis superficial dan memerlukan pendalaman',
                    'Penulisan memadai namun perlu perbaikan struktur'
                ]
            ],
            '50-59' => [
                'grade' => 'C',
                'description' => 'Needs Improvement - Di bawah standar, perlu revisi signifikan',
                'criteria' => [
                    'Orisinalitas kurang, banyak kesamaan dengan karya lain',
                    'Metodologi lemah atau tidak sesuai',
                    'Analisis dangkal dengan interpretasi yang kurang tepat',
                    'Penulisan di bawah standar akademik'
                ]
            ],
            '0-49' => [
                'grade' => 'F',
                'description' => 'Poor - Tidak memenuhi standar minimum, perlu penulisan ulang',
                'criteria' => [
                    'Kurang orisinalitas, indikasi plagiasi atau duplikasi',
                    'Metodologi tidak tepat atau tidak dijelaskan',
                    'Analisis tidak memadai atau salah',
                    'Penulisan buruk dengan banyak kesalahan struktur dan bahasa'
                ]
            ]
        ];
    }

    /**
     * Get grade untuk SPC scoring (0-100 scale)
     * 
     * @return string
     */
    public function getSpcGradeAttribute()
    {
        $score = $this->total_score;
        
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B+';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C+';
        if ($score >= 50) return 'C';
        
        return 'F';
    }

    /**
     * Calculate victory points berdasarkan ranking
     * 
     * @param int $rank
     * @return int
     */
    public static function calculateVictoryPoints($rank)
    {
        $victoryPoints = self::getVictoryPointSystem();
        return $victoryPoints[$rank] ?? 0;
    }

    /**
     * Get grade untuk EDC scoring (50-100 scale)
     * 
     * @return string
     */
    public function getEdcGradeAttribute()
    {
        $score = $this->total_score;
        
        if ($score >= 96) return 'A+';
        if ($score >= 91) return 'A';
        if ($score >= 86) return 'A-';
        if ($score >= 81) return 'B+';
        if ($score >= 76) return 'B';
        if ($score >= 71) return 'B-';
        if ($score >= 66) return 'C+';
        if ($score >= 61) return 'C';
        if ($score >= 56) return 'C-';
        if ($score >= 50) return 'D';
        
        return 'F';
    }

    /**
     * Check if this is an EDC competition score
     * 
     * @return bool
     */
    public function isEdcScore()
    {
        return $this->competition && 
               $this->competition->isEdcCompetition();
    }

    /**
     * Check if this is a KDBI competition score
     * 
     * @return bool
     */
    public function isKdbiScore()
    {
        return $this->competition && 
               $this->competition->isKdbiCompetition();
    }

    /**
     * Check if this is an SPC competition score
     * 
     * @return bool
     */
    public function isSpcScore()
    {
        return $this->competition && 
               $this->competition->isSpcCompetition();
    }

    /**
     * Get grade untuk KDBI scoring (50-100 scale)
     * 
     * @return string
     */
    public function getKdbiGradeAttribute()
    {
        $score = $this->total_score;
        
        if ($score >= 96) return 'A+';
        if ($score >= 91) return 'A';
        if ($score >= 86) return 'A-';
        if ($score >= 81) return 'B+';
        if ($score >= 76) return 'B';
        if ($score >= 71) return 'B-';
        if ($score >= 66) return 'C+';
        if ($score >= 61) return 'C';
        if ($score >= 56) return 'C-';
        if ($score >= 50) return 'D';
        
        return 'F';
    }

    /**
     * Validate criteria scores untuk EDC
     * 
     * @return array
     */
    public function validateEdcCriterias()
    {
        $errors = [];
        
        if (!$this->criteria_scores || !is_array($this->criteria_scores)) {
            $errors[] = 'Criteria scores harus diisi';
            return $errors;
        }

        foreach ($this->criteria_scores as $criteria => $score) {
            if (!self::isValidEdcScore($score)) {
                $errors[] = "Score untuk {$criteria} harus antara 50-100";
            }
        }

        return $errors;
    }
}
