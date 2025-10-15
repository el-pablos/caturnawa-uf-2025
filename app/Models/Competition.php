<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Competition untuk mengelola data kompetisi/lomba
 * 
 * Kelas ini menangani semua operasi CRUD untuk kompetisi
 * termasuk kategori, harga, dan periode pendaftaran
 */
class Competition extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atribut yang dapat diisi secara mass assignment
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'theme',
        'price',
        'price_unas_student',
        'price_external_student',
        'early_bird_price',
        'early_bird_deadline',
        'registration_start',
        'registration_end',
        'submission_start',
        'submission_end',
        'judging_start',
        'judging_end',
        'announcement_date',
        'registration_deadline',
        'round1_date',
        'semifinal_date',
        'final_date',
        'competition_start',
        'competition_end',
        'max_participants',
        'min_team_members',
        'max_team_members',
        'requirements',
        'prizes',
        'rules',
        'image',
        'is_active',
        'status',
        'is_team_competition',
        'allow_individual',
        'submission_deadline',
        'result_announcement',
        'prize_amount',
        'type',
        'short_description',
        'contact_person',
        'contact_email',
        'contact_phone',
        'contact_person_name',
        'contact_person_whatsapp',
        'whatsapp_group_link',
        'terms_conditions',
        'judging_criteria',
        'certificate_template',
        'is_featured',
        'view_count',
        'show_leaderboard',
        'registration_count',
        'upload_requirements',
        'document_requirements',
        'guidelines',
        'submission_formats',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     *
     * @var array<string, string>
     */
    protected $casts = [
        'early_bird_deadline' => 'datetime',
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'submission_start' => 'datetime',
        'submission_end' => 'datetime',
        'judging_start' => 'datetime',
        'judging_end' => 'datetime',
        'announcement_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'round1_date' => 'datetime',
        'semifinal_date' => 'datetime',
        'final_date' => 'datetime',
        'competition_start' => 'datetime',
        'competition_end' => 'datetime',
        'submission_deadline' => 'datetime',
        'result_announcement' => 'datetime',
        'requirements' => 'array',
        'prizes' => 'array',
        'rules' => 'array',
        'is_active' => 'boolean',
        'is_team_competition' => 'boolean',
        'allow_individual' => 'boolean',
        'price' => 'decimal:2',
        'price_unas_student' => 'decimal:2',
        'price_external_student' => 'decimal:2',
        'show_leaderboard' => 'boolean',
        'early_bird_price' => 'decimal:2',
        'prize_amount' => 'decimal:2',
        'judging_criteria' => 'array',
        'is_featured' => 'boolean',
        'view_count' => 'integer',
        'registration_count' => 'integer',
        'upload_requirements' => 'array',
        'document_requirements' => 'array',
        'submission_formats' => 'array',
    ];

    /**
     * Konstanta untuk kategori kompetisi (sektor event)
     */
    const CATEGORIES = [
        'event_dcc' => 'Digital Content Competition',
        'event_debate' => 'Debate Competition (EDC/KDBI)', 
        'event_scientific_paper' => 'Scientific Paper Competition (SPC)',
        // Legacy categories for backward compatibility
        'debate_competition' => 'Debate Competition',
        'short_movie' => 'Short Movie Competition',
        'infografis' => 'Infografis',
        'spc' => 'Scientific Paper Competition',
        'karya_ilmiah' => 'Karya Ilmiah',
    ];

    /**
     * EDC UNAS FEST 2025 Timeline Constants
     * Based on official documents: ALUR PESERTA EDC.pdf
     * 
     * Note: The documents show 2025 dates which might need adjustment for actual event year
     */
    const EDC_TIMELINE_2025 = [
        // Registration Phases (sesuai dokumen DOCX)
        'early_bird_start' => '2025-08-25',     // 25 August 2025
        'early_bird_end' => '2025-08-31',       // 31 August 2025
        'phase_1_start' => '2025-09-01',        // 1 September 2025  
        'phase_1_end' => '2025-09-13',          // 13 September 2025
        'phase_2_start' => '2025-09-14',        // 14 September 2025
        'phase_2_end' => '2025-09-26',          // 26 September 2025
        
        // Competition Events (sesuai dokumen PDF)
        'webinar_technical_meeting' => '2025-09-27',  // Saturday, 27 September 2025
        'preliminary_day_1' => '2025-10-13',          // Monday, 13 October 2025  
        'preliminary_day_2' => '2025-10-14',          // Tuesday, 14 October 2025
        'semifinal_date' => '2025-10-15',             // Wednesday, 15 October 2025
        'final_date' => '2025-10-27',                 // Monday, 27 October 2025
        'award_ceremony' => '2025-11-10',             // Monday, 10 November 2025
    ];

    /**
     * Get adjusted EDC timeline for current year
     * This method adjusts the timeline based on current year if needed
     * 
     * @param int|null $year Override year (default: current year)
     * @return array
     */
    public static function getAdjustedEdcTimeline($year = null)
    {
        $targetYear = $year ?? now()->year;
        $adjustedTimeline = [];
        
        foreach (self::EDC_TIMELINE_2025 as $key => $date) {
            $carbonDate = \Carbon\Carbon::parse($date);
            $adjustedDate = $carbonDate->setYear($targetYear);
            $adjustedTimeline[$key] = $adjustedDate->format('Y-m-d');
        }
        
        return $adjustedTimeline;
    }

    /**
     * EDC Pricing Constants
     */
    const EDC_PRICING_2025 = [
        'early_bird' => 150000,  // Rp.150.000/Team (25-31 Aug 2025)
        'phase_1' => 250000,     // Rp.250.000/Team (1-13 Sept 2025)
        'phase_2' => 300000,     // Rp.300.000/Team (14-26 Sept 2025)
    ];

    /**
     * KDBI UNAS FEST 2025 Timeline Constants
     * Based on official documents: ALUR PESERTA KDBI.pdf
     * 
     * Note: The documents show 2025 dates which might need adjustment for actual event year
     */
    const KDBI_TIMELINE_2025 = [
        // Registration Phases (sesuai dokumen KDBI)
        'early_bird_start' => '2025-08-25',     // 25 August 2025
        'early_bird_end' => '2025-08-31',       // 31 August 2025
        'phase_1_start' => '2025-09-01',        // 1 September 2025  
        'phase_1_end' => '2025-09-13',          // 13 September 2025
        'phase_2_start' => '2025-09-14',        // 14 September 2025
        'phase_2_end' => '2025-09-26',          // 26 September 2025
        
        // Competition Events (sesuai dokumen PDF)
        'webinar_technical_meeting' => '2025-09-27',  // Saturday, 27 September 2025
        'preliminary_day_1' => '2025-10-13',          // Monday, 13 October 2025  
        'preliminary_day_2' => '2025-10-14',          // Tuesday, 14 October 2025
        'semifinal_date' => '2025-10-15',             // Wednesday, 15 October 2025
        'final_date' => '2025-10-27',                 // Monday, 27 October 2025
        'award_ceremony' => '2025-11-10',             // Monday, 10 November 2025
    ];

    /**
     * Get adjusted KDBI timeline for current year
     * This method adjusts the timeline based on current year if needed
     * 
     * @param int|null $year Override year (default: current year)
     * @return array
     */
    public static function getAdjustedKdbiTimeline($year = null)
    {
        $targetYear = $year ?? now()->year;
        $adjustedTimeline = [];
        
        foreach (self::KDBI_TIMELINE_2025 as $key => $date) {
            $carbonDate = \Carbon\Carbon::parse($date);
            $adjustedDate = $carbonDate->setYear($targetYear);
            $adjustedTimeline[$key] = $adjustedDate->format('Y-m-d');
        }
        
        return $adjustedTimeline;
    }

    /**
     * KDBI Pricing Constants
     */
    const KDBI_PRICING_2025 = [
        'early_bird' => 150000,  // Rp.150.000/Tim (25-31 Aug 2025)
        'phase_1' => 250000,     // Rp.250.000/Tim (1-13 Sept 2025)
        'phase_2' => 300000,     // Rp.300.000/Tim (14-26 Sept 2025)
    ];

    /**
     * KDBI Competition Rules
     */
    const KDBI_RULES_2025 = [
        'Peserta merupakan mahasiswa/i aktif program sarjana yang terdaftar di PDDikti (Pangkalan Data Pendidikan Tinggi) untuk seluruh universitas negeri dan swasta di Indonesia dari berbagai program studi.',
        'Peserta merupakan tim yang terdiri dari 2 individu (anggota tim diperbolehkan seluruhnya laki-laki, perempuan, ataupun campuran). Posisi pembicara yang peserta pilih tidak dapat diubah hingga kompetisi berakhir.',
        'Peserta berasal dari universitas yang sama, diperbolehkan beda program studi, fakultas, ataupun semester.',
        'Peserta wajib mengikuti peraturan yang telah ditentukan dan dicantumkan pada buku pedoman kegiatan KDBI UNAS FEST 2025.',
        'Peserta yang telah membayar biaya pendaftaran, kemudian membatalkan keikutsertaannya, maka biaya tersebut tidak dapat dikembalikan.',
        'Setiap tim wajib untuk membuat nama tim yang sesuai dengan tema UNAS FEST 2025, tanpa menyinggung unsur Suku, Agama, Ras, dan Antar Golongan (SARA).',
        'Debat akan dilakukan secara daring menggunakan Zoom Meeting yang disediakan oleh panitia UNAS FEST 2025.',
        'Tim akan melalui babak Penyisihan (24 tim → 12 tim), Semifinal (12 tim → 4 tim), dan Babak Final (4 tim).',
        'Penilaian meliputi Verbal Adjudication dengan penjelasan evaluasi dan peringkat, serta Silent Round tanpa pengumuman hasil langsung.',
        'Sistem Reset Point berlaku dimana poin kumulatif dari babak penyisihan tidak dibawa ke babak final.',
    ];

    /**
     * KDBI Assessment Types
     */
    const KDBI_ASSESSMENT_TYPES = [
        'verbal_adjudication' => 'Verbal Adjudication - Penjelasan lisan dari juri setelah debat selesai',
        'silent_round' => 'Silent Round - Babak debat tanpa pengumuman hasil langsung dan tanpa verbal adjudication',
        'reset_point' => 'Reset Point - Poin kumulatif dari babak penyisihan tidak dibawa ke babak berikutnya (final round)',
    ];

    /**
     * SPC UNAS FEST 2025 Timeline Constants
     * Based on scientific paper competition standards
     * 
     * Note: Academic timeline with submission, review, and presentation phases
     */
    const SPC_TIMELINE_2025 = [
        // Registration Phases
        'early_bird_start' => '2025-08-01',         // 1 August 2025
        'early_bird_end' => '2025-08-31',           // 31 August 2025
        'regular_registration_start' => '2025-09-01', // 1 September 2025
        'regular_registration_end' => '2025-09-30',   // 30 September 2025
        
        // Submission Phases  
        'abstract_submission_deadline' => '2025-09-15',    // 15 September 2025
        'full_paper_submission_deadline' => '2025-10-10',  // 10 October 2025
        'plagiarism_check_deadline' => '2025-10-12',       // 12 October 2025
        
        // Review Process
        'review_assignment_start' => '2025-10-13',         // 13 October 2025
        'review_period_start' => '2025-10-15',             // 15 October 2025
        'review_period_end' => '2025-11-15',               // 15 November 2025
        'review_notification' => '2025-11-20',             // 20 November 2025
        
        // Revision Process
        'revision_submission_start' => '2025-11-21',       // 21 November 2025
        'revision_submission_deadline' => '2025-12-01',    // 1 December 2025
        'final_paper_deadline' => '2025-12-05',            // 5 December 2025
        
        // Presentation & Award
        'presentation_date' => '2025-12-15',               // 15 December 2025
        'award_ceremony' => '2025-12-20',                  // 20 December 2025
    ];

    /**
     * Get adjusted SPC timeline for current year
     * This method adjusts the timeline based on current year if needed
     * 
     * @param int|null $year Override year (default: current year)
     * @return array
     */
    public static function getAdjustedSpcTimeline($year = null)
    {
        $targetYear = $year ?? now()->year;
        $adjustedTimeline = [];
        
        foreach (self::SPC_TIMELINE_2025 as $key => $date) {
            $carbonDate = \Carbon\Carbon::parse($date);
            $adjustedDate = $carbonDate->setYear($targetYear);
            $adjustedTimeline[$key] = $adjustedDate->format('Y-m-d');
        }
        
        return $adjustedTimeline;
    }

    /**
     * SPC Pricing Constants
     */
    const SPC_PRICING_2025 = [
        'early_bird' => 75000,      // Rp.75.000 (1-31 Aug 2025)
        'regular' => 100000,        // Rp.100.000 (1-30 Sept 2025)  
        'international' => 150000,  // Rp.150.000 (International participants)
    ];

    /**
     * SPC Research Fields
     */
    const SPC_RESEARCH_FIELDS = [
        'technology' => 'Technology and Innovation',
        'health' => 'Health and Medicine',
        'environment' => 'Environmental Science and Sustainability',
        'social' => 'Social Sciences and Humanities',
        'economics' => 'Economics and Business Management',
        'education' => 'Education and Learning Innovation',
        'agriculture' => 'Agriculture and Food Security',
        'energy' => 'Renewable Energy and Green Technology',
    ];

    /**
     * SPC Competition Rules
     */
    const SPC_RULES_2025 = [
        'Peserta merupakan mahasiswa/i aktif program sarjana, magister, atau doktor yang terdaftar di perguruan tinggi Indonesia atau luar negeri.',
        'Karya tulis harus original dan belum pernah dipublikasikan dalam jurnal atau prosiding manapun.',
        'Tim dapat terdiri dari 1-3 orang (individual atau kelompok) dari universitas yang sama atau berbeda.',
        'Mengikuti format penulisan ilmiah yang telah ditentukan dengan template yang disediakan panitia.',
        'Naskah ditulis maksimal 15 halaman (tidak termasuk cover, daftar pustaka, dan lampiran).',
        'Menggunakan referensi minimal 15 sumber (jurnal ilmiah, buku, prosiding) dengan 70% referensi terbaru dari 10 tahun terakhir.',
        'Similarity index maksimal 20% berdasarkan hasil Turnitin, Grammarly, atau tools similarity lainnya.',
        'Menggunakan bahasa Indonesia atau bahasa Inggris yang baik dan benar sesuai kaidah penulisan ilmiah.',
        'Menyertakan abstract dalam bahasa Inggris maksimal 300 kata dengan 5-7 keywords.',
        'Peserta wajib menghadiri sesi presentasi final jika lolos ke tahap tersebut.',
        'Keputusan juri bersifat final dan tidak dapat diganggu gugat.',
        'Panitia berhak mendiskualifikasi peserta yang terbukti melakukan plagiasi atau pelanggaran etika akademik.',
    ];

    /**
     * SPC Assessment Types
     */
    const SPC_ASSESSMENT_TYPES = [
        'blind_review' => 'Blind Review - Penilaian oleh reviewer tanpa mengetahui identitas penulis',
        'presentation_evaluation' => 'Presentation Evaluation - Penilaian berdasarkan presentasi final',
        'plagiarism_check' => 'Plagiarism Check - Pengecekan similarity dan orisinalitas karya',
        'academic_rigor' => 'Academic Rigor - Penentuanbstrak berdasarkan standar penulisan ilmiah',
    ];

    /**
     * EDC Competition Rules
     */
    const EDC_RULES_2025 = [
        'Participants are active undergraduate students registered in PDDikti (Pangkalan Data Pendidikan Tinggi) for state and private universities in Indonesia from various study programs.',
        'Participants are teams consisting of two individuals (team members can be male, female, or mixed). The speaker position that participants choose cannot be changed until the competition ends.',
        'Participants come from the same university and can be from different study programs, faculties, or semesters.',
        'Participants must follow the rules that have been determined and listed in the EDC UNAS FEST 2025 guidebook.',
        'Participants who have paid the registration fee and somehow cancelled their participation will not get a refund.',
        'All participants must create a group name that relates to the UNAS FEST 2025 theme, without offending any elements of Ethnicity, Religion, Race, and Intergroup (SARA).',
        'The debate will be conducted online via Zoom Meeting, using a meeting link provided by the UNAS FEST 2025 committees.',
        'Teams will progress through Preliminary Rounds (24 teams → 12 teams), Semifinal (12 teams → 4 teams), and Final Round (4 teams).',
        'Assessment includes Verbal Adjudication with evaluation and ranking explanation, and Silent Rounds without immediate result announcement.',
        'Reset Point system applies where cumulative points from preliminary rounds are not carried over to the final round.',
    ];

    /**
     * EDC Assessment Types
     */
    const EDC_ASSESSMENT_TYPES = [
        'verbal_adjudication' => 'Verbal Adjudication - Verbal explanation from the judge after the debate concludes',
        'silent_round' => 'Silent Round - A debate round without immediate result announcement and without verbal adjudication',
        'reset_point' => 'Reset Point - Cumulative points from preliminary rounds are not carried over to final round',
    ];

    /**
     * Relasi dengan model Registration (pendaftaran)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Relasi dengan registrasi yang sudah dikonfirmasi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function confirmedRegistrations()
    {
        return $this->hasMany(Registration::class)->where('status', 'confirmed');
    }

    /**
     * Relasi dengan model CompetitionDescription
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function descriptions()
    {
        return $this->hasMany(CompetitionDescription::class);
    }

    /**
     * Relasi dengan model CompetitionRound
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rounds()
    {
        return $this->hasMany(CompetitionRound::class);
    }

    /**
     * Relasi dengan model CompetitionScoringCriteria
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scoringCriteria()
    {
        return $this->hasMany(CompetitionScoringCriteria::class);
    }

    /**
     * Relasi dengan model SubmissionGuideline
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissionGuidelines()
    {
        return $this->hasMany(SubmissionGuideline::class);
    }

    /**
     * Relasi ke leaderboard entries
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaderboardEntries()
    {
        return $this->hasMany(LeaderboardEntry::class);
    }

    /**
     * Relasi dengan model Score (penilaian)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    /**
     * Relasi dengan model Submission (karya peserta)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
    
    /**
     * Relasi dengan CompetitionRequirement
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function competitionRequirements()
    {
        return $this->hasMany(CompetitionRequirement::class);
    }
    
    /**
     * Relasi dengan CompetitionCriteria
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function competitionCriterias()
    {
        return $this->hasMany(CompetitionCriteria::class)->orderBy('order_index');
    }
    
    /**
     * Relasi dengan CompetitionJudge
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function competitionJudges()
    {
        return $this->hasMany(CompetitionJudge::class)->where('is_active', true)->orderBy('order_index');
    }

    /**
     * Relasi many-to-many dengan User (juries)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function juries()
    {
        return $this->belongsToMany(User::class, 'competition_juries', 'competition_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Relasi untuk registrasi yang sudah dikonfirmasi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paidRegistrations()
    {
        return $this->hasMany(Registration::class)->where('status', 'paid');
    }

    /**
     * Scope untuk kompetisi yang aktif
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk kompetisi yang sedang buka pendaftaran
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpenRegistration($query)
    {
        return $query->where('is_active', true)
            ->where('registration_start', '<=', now())
            ->where('registration_end', '>=', now());
    }

    /**
     * Scope berdasarkan kategori
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Accessor untuk mendapatkan URL gambar kompetisi
     * 
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/competitions/' . $this->image);
        }
        
        return asset('images/default-competition.png');
    }

    /**
     * Accessor untuk status pendaftaran
     * 
     * @return string
     */
    public function getRegistrationStatusAttribute()
    {
        $now = now();
        
        if ($now < $this->registration_start) {
            return 'upcoming';
        } elseif ($now > $this->registration_end) {
            return 'closed';
        } else {
            return 'open';
        }
    }

    /**
     * Accessor untuk mendapatkan harga yang berlaku
     * 
     * @return float
     */
    public function getCurrentPriceAttribute()
    {
        if ($this->early_bird_deadline && now() <= $this->early_bird_deadline) {
            return $this->early_bird_price ?? $this->price;
        }
        
        return $this->price;
    }

    /**
     * Cek apakah masih dalam periode early bird
     * 
     * @return bool
     */
    public function isEarlyBird()
    {
        return $this->early_bird_deadline && now() <= $this->early_bird_deadline;
    }

    /**
     * Cek apakah pendaftaran masih terbuka
     * 
     * @return bool
     */
    public function isRegistrationOpen()
    {
        return $this->is_active && 
               now() >= $this->registration_start && 
               now() <= $this->registration_end;
    }

    /**
     * Cek apakah sudah mencapai batas maksimal peserta
     * 
     * @return bool
     */
    public function isFullyBooked()
    {
        if (!$this->max_participants) {
            return false;
        }
        
        return $this->registrations()->where('status', 'confirmed')->count() >= $this->max_participants;
    }

    /**
     * Mendapatkan jumlah peserta terdaftar
     * 
     * @return int
     */
    public function getRegisteredParticipantsCount()
    {
        return $this->registrations()->where('status', 'confirmed')->count();
    }

    /**
     * Mendapatkan total pendapatan dari kompetisi
     *
     * @return float
     */
    public function getTotalRevenue()
    {
        return $this->registrations()
            ->whereHas('payment', function($query) {
                $query->where('status', 'success');
            })
            ->sum('amount');
    }

    /**
     * Get days left until registration deadline
     *
     * @return int|null
     */
    public function getDaysLeftAttribute()
    {
        $deadline = $this->registration_end ?? $this->registration_deadline;

        if (!$deadline) {
            return null;
        }

        $now = now();

        if ($deadline <= $now) {
            return 0;
        }

        return $now->diffInDays($deadline);
    }

    /**
     * Get competition timeline
     *
     * @return array
     */
    public function getTimelineAttribute()
    {
        $timeline = [];

        // For specific competition categories, use detailed timelines
        if ($this->category === 'event_debate') {
            // Check if this is KDBI (Indonesian debate) or EDC (English debate)
            if (stripos($this->name, 'KDBI') !== false || stripos($this->name, 'Indonesia') !== false) {
                return $this->getKdbiTimeline();
            } else {
                return $this->getEdcTimeline();
            }
        } elseif ($this->category === 'event_scientific_paper') {
            return $this->getSpcTimeline();
        } elseif ($this->category === 'event_dcc') {
            return $this->getDccTimeline();
        }

        // Default timeline for other competitions
        if ($this->registration_start) {
            $timeline[] = [
                'title' => 'Pendaftaran Dibuka',
                'date' => $this->registration_start,
                'status' => $this->registration_start <= now() ? 'completed' : 'upcoming',
                'icon' => 'bi-calendar-plus',
                'color' => 'success'
            ];
        }

        $deadline = $this->registration_end ?? $this->registration_deadline;
        if ($deadline) {
            $timeline[] = [
                'title' => 'Batas Akhir Pendaftaran',
                'date' => $deadline,
                'status' => $deadline <= now() ? 'completed' : 'upcoming',
                'icon' => 'bi-calendar-x',
                'color' => 'warning'
            ];
        }

        $round1 = $this->competition_start ?? $this->round1_date;
        if ($round1) {
            $timeline[] = [
                'title' => 'Babak Penyisihan',
                'date' => $round1,
                'status' => $round1 <= now() ? 'completed' : 'upcoming',
                'icon' => 'bi-trophy',
                'color' => 'primary'
            ];
        }

        $semifinal = $this->competition_start?->addDays(5) ?? $this->semifinal_date;
        if ($semifinal) {
            $timeline[] = [
                'title' => 'Semifinal',
                'date' => $semifinal,
                'status' => $semifinal <= now() ? 'completed' : 'upcoming',
                'icon' => 'bi-award',
                'color' => 'info'
            ];
        }

        $final = $this->competition_end ?? $this->final_date;
        if ($final) {
            $timeline[] = [
                'title' => 'Final',
                'date' => $final,
                'status' => $final <= now() ? 'completed' : 'upcoming',
                'icon' => 'bi-trophy-fill',
                'color' => 'danger'
            ];
        }

        if ($this->result_announcement) {
            $timeline[] = [
                'title' => 'Pengumuman Hasil',
                'date' => $this->result_announcement,
                'status' => $this->result_announcement <= now() ? 'completed' : 'upcoming',
                'icon' => 'bi-megaphone',
                'color' => 'success'
            ];
        }

        // Sort by date
        usort($timeline, function ($a, $b) {
            return $a['date']->timestamp <=> $b['date']->timestamp;
        });

        return $timeline;
    }

    /**
     * Get is_team attribute (alias for is_team_competition)
     *
     * @return bool
     */
    public function getIsTeamAttribute()
    {
        return $this->is_team_competition;
    }

    /**
     * Get EDC specific timeline sesuai dokumen
     *
     * @return array
     */
    public function getEdcTimeline()
    {
        // Use adjusted timeline for current/appropriate year
        $adjustedDates = self::getAdjustedEdcTimeline();
        
        $timeline = [
            [
                'title' => 'Early Bird Registration',
                'description' => 'Rp.150.000/Team',
                'date' => \Carbon\Carbon::parse($adjustedDates['early_bird_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['early_bird_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['early_bird_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['early_bird_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-calendar-plus',
                'color' => 'success'
            ],
            [
                'title' => 'Phase 1 Registration',
                'description' => 'Rp.250.000/Team',
                'date' => \Carbon\Carbon::parse($adjustedDates['phase_1_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['phase_1_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['phase_1_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['phase_1_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-calendar-check',
                'color' => 'warning'
            ],
            [
                'title' => 'Phase 2 Registration',
                'description' => 'Rp.300.000/Team',
                'date' => \Carbon\Carbon::parse($adjustedDates['phase_2_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['phase_2_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['phase_2_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['phase_2_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-calendar-x',
                'color' => 'danger'
            ],
            [
                'title' => 'Webinar & Technical Meeting',
                'description' => 'Tips and tricks for debate competitions via Zoom',
                'date' => \Carbon\Carbon::parse($adjustedDates['webinar_technical_meeting']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['webinar_technical_meeting']) ? 'completed' : 'upcoming',
                'icon' => 'bi-camera-video',
                'color' => 'info'
            ],
            [
                'title' => 'Preliminary Round Day 1',
                'description' => '24 Teams - Online via Zoom Meeting',
                'date' => \Carbon\Carbon::parse($adjustedDates['preliminary_day_1']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['preliminary_day_1']) ? 'completed' : 'upcoming',
                'icon' => 'bi-trophy',
                'color' => 'primary'
            ],
            [
                'title' => 'Preliminary Round Day 2',
                'description' => '24 Teams - Online via Zoom Meeting',
                'date' => \Carbon\Carbon::parse($adjustedDates['preliminary_day_2']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['preliminary_day_2']) ? 'completed' : 'upcoming',
                'icon' => 'bi-trophy',
                'color' => 'primary'
            ],
            [
                'title' => 'Semifinal Debate',
                'description' => '12 Teams qualified from Preliminary Rounds',
                'date' => \Carbon\Carbon::parse($adjustedDates['semifinal_date']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['semifinal_date']) ? 'completed' : 'upcoming',
                'icon' => 'bi-award',
                'color' => 'info'
            ],
            [
                'title' => 'Final Round',
                'description' => '4 Teams - Determine 1st, 2nd, 3rd place and best speaker',
                'date' => \Carbon\Carbon::parse($adjustedDates['final_date']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['final_date']) ? 'completed' : 'upcoming',
                'icon' => 'bi-trophy-fill',
                'color' => 'danger'
            ],
            [
                'title' => 'Award Ceremony',
                'description' => 'UNAS Cyber Auditorium - All committees, adjudicators and participants',
                'date' => \Carbon\Carbon::parse($adjustedDates['award_ceremony']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['award_ceremony']) ? 'completed' : 'upcoming',
                'icon' => 'bi-megaphone',
                'color' => 'success'
            ]
        ];

        return $timeline;
    }

    /**
     * Get KDBI specific timeline sesuai dokumen
     *
     * @return array
     */
    public function getKdbiTimeline()
    {
        // Use adjusted timeline for current/appropriate year
        $adjustedDates = self::getAdjustedKdbiTimeline();
        
        $timeline = [
            [
                'title' => 'Early Bird Registration',
                'description' => 'Rp.150.000/Tim',
                'date' => \Carbon\Carbon::parse($adjustedDates['early_bird_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['early_bird_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['early_bird_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['early_bird_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-calendar-plus',
                'color' => 'success'
            ],
            [
                'title' => 'Phase 1 Registration',
                'description' => 'Rp.250.000/Tim',
                'date' => \Carbon\Carbon::parse($adjustedDates['phase_1_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['phase_1_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['phase_1_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['phase_1_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-calendar-check',
                'color' => 'warning'
            ],
            [
                'title' => 'Phase 2 Registration',
                'description' => 'Rp.300.000/Tim',
                'date' => \Carbon\Carbon::parse($adjustedDates['phase_2_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['phase_2_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['phase_2_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['phase_2_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-calendar-x',
                'color' => 'danger'
            ],
            [
                'title' => 'Webinar & Technical Meeting',
                'description' => 'Tips dan trik kompetisi debat via Zoom',
                'date' => \Carbon\Carbon::parse($adjustedDates['webinar_technical_meeting']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['webinar_technical_meeting']) ? 'completed' : 'upcoming',
                'icon' => 'bi-camera-video',
                'color' => 'info'
            ],
            [
                'title' => 'Babak Penyisihan Hari 1',
                'description' => '24 Tim - Secara daring via Zoom Meeting',
                'date' => \Carbon\Carbon::parse($adjustedDates['preliminary_day_1']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['preliminary_day_1']) ? 'completed' : 'upcoming',
                'icon' => 'bi-trophy',
                'color' => 'primary'
            ],
            [
                'title' => 'Babak Penyisihan Hari 2',
                'description' => '24 Tim - Secara daring via Zoom Meeting',
                'date' => \Carbon\Carbon::parse($adjustedDates['preliminary_day_2']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['preliminary_day_2']) ? 'completed' : 'upcoming',
                'icon' => 'bi-trophy',
                'color' => 'primary'
            ],
            [
                'title' => 'Semifinal Debat',
                'description' => '12 Tim yang lolos dari Babak Penyisihan',
                'date' => \Carbon\Carbon::parse($adjustedDates['semifinal_date']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['semifinal_date']) ? 'completed' : 'upcoming',
                'icon' => 'bi-award',
                'color' => 'info'
            ],
            [
                'title' => 'Final Debat',
                'description' => '4 Tim - Menentukan juara 1, 2, 3, dan best speaker',
                'date' => \Carbon\Carbon::parse($adjustedDates['final_date']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['final_date']) ? 'completed' : 'upcoming',
                'icon' => 'bi-trophy-fill',
                'color' => 'danger'
            ],
            [
                'title' => 'Acara Pemberian Penghargaan',
                'description' => 'UNAS Cyber Auditorium - Seluruh panitia, juri, dan peserta',
                'date' => \Carbon\Carbon::parse($adjustedDates['award_ceremony']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['award_ceremony']) ? 'completed' : 'upcoming',
                'icon' => 'bi-megaphone',
                'color' => 'success'
            ]
        ];

        return $timeline;
    }

    /**
     * Get SPC specific timeline sesuai standar kompetisi karya ilmiah
     *
     * @return array
     */
    public function getSpcTimeline()
    {
        // Use adjusted timeline for current/appropriate year
        $adjustedDates = self::getAdjustedSpcTimeline();
        
        $timeline = [
            [
                'title' => 'Early Bird Registration',
                'description' => 'Rp.75.000 - Pendaftaran tahap awal',
                'date' => \Carbon\Carbon::parse($adjustedDates['early_bird_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['early_bird_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['early_bird_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['early_bird_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-calendar-plus',
                'color' => 'success'
            ],
            [
                'title' => 'Regular Registration',
                'description' => 'Rp.100.000 - Pendaftaran reguler',
                'date' => \Carbon\Carbon::parse($adjustedDates['regular_registration_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['regular_registration_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['regular_registration_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['regular_registration_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-calendar-check',
                'color' => 'warning'
            ],
            [
                'title' => 'Abstract Submission Deadline',
                'description' => 'Batas akhir pengumpulan abstrak',
                'date' => \Carbon\Carbon::parse($adjustedDates['abstract_submission_deadline']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['abstract_submission_deadline']) ? 'completed' : 'upcoming',
                'icon' => 'bi-file-text',
                'color' => 'info'
            ],
            [
                'title' => 'Full Paper Submission Deadline',
                'description' => 'Batas akhir pengumpulan karya tulis lengkap',
                'date' => \Carbon\Carbon::parse($adjustedDates['full_paper_submission_deadline']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['full_paper_submission_deadline']) ? 'completed' : 'upcoming',
                'icon' => 'bi-file-earmark-text',
                'color' => 'primary'
            ],
            [
                'title' => 'Plagiarism Check Deadline',
                'description' => 'Batas akhir pengumpulan laporan similarity (Turnitin/Grammarly)',
                'date' => \Carbon\Carbon::parse($adjustedDates['plagiarism_check_deadline']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['plagiarism_check_deadline']) ? 'completed' : 'upcoming',
                'icon' => 'bi-shield-check',
                'color' => 'secondary'
            ],
            [
                'title' => 'Review Period',
                'description' => 'Periode penilaian oleh reviewer ahli',
                'date' => \Carbon\Carbon::parse($adjustedDates['review_period_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['review_period_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['review_period_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['review_period_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-search',
                'color' => 'info'
            ],
            [
                'title' => 'Review Notification',
                'description' => 'Pengumuman hasil review dan feedback',
                'date' => \Carbon\Carbon::parse($adjustedDates['review_notification']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['review_notification']) ? 'completed' : 'upcoming',
                'icon' => 'bi-bell',
                'color' => 'warning'
            ],
            [
                'title' => 'Revision Submission Deadline',
                'description' => 'Batas akhir pengumpulan revisi berdasarkan feedback reviewer',
                'date' => \Carbon\Carbon::parse($adjustedDates['revision_submission_deadline']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['revision_submission_deadline']) ? 'completed' : 'upcoming',
                'icon' => 'bi-arrow-repeat',
                'color' => 'primary'
            ],
            [
                'title' => 'Final Paper Deadline',
                'description' => 'Batas akhir pengumpulan naskah final',
                'date' => \Carbon\Carbon::parse($adjustedDates['final_paper_deadline']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['final_paper_deadline']) ? 'completed' : 'upcoming',
                'icon' => 'bi-file-check',
                'color' => 'success'
            ],
            [
                'title' => 'Presentation Session',
                'description' => 'Sesi presentasi final peserta terbaik',
                'date' => \Carbon\Carbon::parse($adjustedDates['presentation_date']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['presentation_date']) ? 'completed' : 'upcoming',
                'icon' => 'bi-presentation',
                'color' => 'danger'
            ],
            [
                'title' => 'Award Ceremony',
                'description' => 'Pemberian penghargaan dan publikasi hasil',
                'date' => \Carbon\Carbon::parse($adjustedDates['award_ceremony']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['award_ceremony']) ? 'completed' : 'upcoming',
                'icon' => 'bi-trophy',
                'color' => 'success'
            ]
        ];

        return $timeline;
    }

    /**
     * Check if this is EDC competition
     *
     * @return bool
     */
    public function isEdcCompetition()
    {
        return $this->category === 'event_debate' && (stripos($this->name, 'EDC') !== false || stripos($this->name, 'English') !== false);
    }

    /**
     * Check if this is KDBI competition
     *
     * @return bool
     */
    public function isKdbiCompetition()
    {
        return $this->category === 'event_debate' && (stripos($this->name, 'KDBI') !== false || stripos($this->name, 'Indonesia') !== false);
    }

    /**
     * Check if this is SPC competition
     *
     * @return bool
     */
    public function isSpcCompetition()
    {
        return $this->category === 'event_scientific_paper';
    }


    /**
     * Check if this is DCC Infographics competition
     *
     * @return bool
     */
    public function isDccInfographicsCompetition()
    {
        return $this->isDccCompetition() && 
               (stripos($this->name, 'Infograf') !== false);
    }

    /**
     * Check if this is DCC Short Video competition
     *
     * @return bool
     */
    public function isDccShortVideoCompetition()
    {
        return $this->isDccCompetition() && 
               (stripos($this->name, 'Short Video') !== false || 
                stripos($this->name, 'Video') !== false);
    }

    /**
     * Get static team configuration for competitions
     *
     * @return array
     */
    public function getStaticTeamConfig()
    {
        if ($this->isEdcCompetition() || $this->isKdbiCompetition()) {
            return [
                'team_size' => 2,
                'team_required' => true,
                'roles' => ['first_speaker', 'second_speaker'],
                'role_names' => ['First Speaker', 'Second Speaker']
            ];
        }
        
        if ($this->isSpcCompetition()) {
            return [
                'team_size' => 1,
                'team_required' => false,
                'individual' => true,
                'roles' => ['author'],
                'role_names' => ['Author']
            ];
        }
        
        if (str_contains(strtolower($this->name), 'infografis')) {
            return [
                'team_size' => 1,
                'team_required' => false,
                'roles' => ['designer'],
                'role_names' => ['Designer']
            ];
        }
        
        if (str_contains(strtolower($this->name), 'video')) {
            return [
                'team_size' => 3,
                'team_required' => true,
                'roles' => ['director', 'writer', 'editor'],
                'role_names' => ['Director', 'Writer/Scriptwriter', 'Editor']
            ];
        }
        
        // Default configuration
        return [
            'team_size' => 1,
            'team_required' => false,
            'roles' => ['participant'],
            'role_names' => ['Participant']
        ];
    }

    /**
     * Create static registration for this competition
     *
     * @param \App\Models\User $user
     * @param array $teamData
     * @return \App\Models\Registration
     */
    public function createStaticRegistration($user, $teamData = [])
    {
        $config = $this->getStaticTeamConfig();
        
        $registration = new Registration();
        $registration->user_id = $user->id;
        $registration->competition_id = $this->id;
        $registration->registration_number = Registration::generateRegistrationNumber();
        $registration->status = 'pending';
        $registration->amount = $this->getCurrentPrice();
        $registration->original_price = $this->price;
        
        // Set basic user info
        $registration->phone = $teamData['phone'] ?? $user->phone;
        $registration->institution = $teamData['institution'] ?? $user->institution;
        $registration->gender = $teamData['gender'] ?? 'male';
        $registration->education_level = $teamData['education_level'] ?? 'university';
        
        // Set team configuration based on competition type
        if ($config['team_required']) {
            $registration->team_name = $teamData['team_name'] ?? ($user->name . ' Team');
            $registration->is_team_competition = true;
            
            // Create static team members based on configuration
            $teamMembers = [];
            for ($i = 0; $i < $config['team_size']; $i++) {
                $memberData = $teamData['members'][$i] ?? [];
                $teamMembers[] = [
                    'name' => $memberData['name'] ?? 'Member ' . ($i + 1),
                    'email' => $memberData['email'] ?? $user->email,
                    'phone' => $memberData['phone'] ?? $user->phone,
                    'university' => $memberData['university'] ?? ($user->institution ?? 'Unknown University'),
                    'faculty' => $memberData['faculty'] ?? 'Unknown Faculty',
                    'study_program' => $memberData['study_program'] ?? 'Unknown Program',
                    'student_id' => $memberData['student_id'] ?? '000000',
                    'semester' => $memberData['semester'] ?? 1,
                    'gender' => $memberData['gender'] ?? 'male',
                    'birth_date' => $memberData['birth_date'] ?? now()->subYears(20)->format('Y-m-d'),
                    'role' => $config['roles'][$i] ?? 'participant',
                    'speaker_position' => $config['roles'][$i] ?? null,
                    'zoom_account_email' => $memberData['zoom_email'] ?? $memberData['email'] ?? $user->email,
                    'language_proficiency_level' => $memberData['language_level'] ?? 'intermediate',
                ];
            }
            $registration->team_members = $teamMembers;
        } else {
            $registration->is_team_competition = false;
            $registration->team_name = null;
        }
        
        $registration->save();
        
        return $registration;
    }

    /**
     * Get current EDC pricing phase
     *
     * @return array
     */
    public function getCurrentEdcPricing()
    {
        $now = now();
        $adjustedDates = self::getAdjustedEdcTimeline();
        
        if ($now <= \Carbon\Carbon::parse($adjustedDates['early_bird_end'])) {
            return [
                'phase' => 'early_bird',
                'price' => self::EDC_PRICING_2025['early_bird'],
                'phase_name' => 'Early Bird',
                'deadline' => $adjustedDates['early_bird_end']
            ];
        }
        
        if ($now <= \Carbon\Carbon::parse($adjustedDates['phase_1_end'])) {
            return [
                'phase' => 'phase_1',
                'price' => self::EDC_PRICING_2025['phase_1'],
                'phase_name' => 'Phase 1',
                'deadline' => $adjustedDates['phase_1_end']
            ];
        }
        
        if ($now <= \Carbon\Carbon::parse($adjustedDates['phase_2_end'])) {
            return [
                'phase' => 'phase_2',
                'price' => self::EDC_PRICING_2025['phase_2'],
                'phase_name' => 'Phase 2',
                'deadline' => $adjustedDates['phase_2_end']
            ];
        }
        
        return [
            'phase' => 'closed',
            'price' => 0,
            'phase_name' => 'Registration Closed',
            'deadline' => null
        ];
    }

    /**
     * Get current KDBI pricing phase
     *
     * @return array
     */
    public function getCurrentKdbiPricing()
    {
        $now = now();
        $adjustedDates = self::getAdjustedKdbiTimeline();
        
        if ($now <= \Carbon\Carbon::parse($adjustedDates['early_bird_end'])) {
            return [
                'phase' => 'early_bird',
                'price' => self::KDBI_PRICING_2025['early_bird'],
                'phase_name' => 'Early Bird',
                'deadline' => $adjustedDates['early_bird_end']
            ];
        }
        
        if ($now <= \Carbon\Carbon::parse($adjustedDates['phase_1_end'])) {
            return [
                'phase' => 'phase_1',
                'price' => self::KDBI_PRICING_2025['phase_1'],
                'phase_name' => 'Phase 1',
                'deadline' => $adjustedDates['phase_1_end']
            ];
        }
        
        if ($now <= \Carbon\Carbon::parse($adjustedDates['phase_2_end'])) {
            return [
                'phase' => 'phase_2',
                'price' => self::KDBI_PRICING_2025['phase_2'],
                'phase_name' => 'Phase 2',
                'deadline' => $adjustedDates['phase_2_end']
            ];
        }
        
        return [
            'phase' => 'closed',
            'price' => 0,
            'phase_name' => 'Pendaftaran Ditutup',
            'deadline' => null
        ];
    }

    /**
     * Get EDC rules
     *
     * @return array
     */
    public function getEdcRules()
    {
        return self::EDC_RULES_2025;
    }

    /**
     * Get current SPC pricing phase
     *
     * @return array
     */
    public function getCurrentSpcPricing()
    {
        $now = now();
        $adjustedDates = self::getAdjustedSpcTimeline();
        
        if ($now <= \Carbon\Carbon::parse($adjustedDates['early_bird_end'])) {
            return [
                'phase' => 'early_bird',
                'price' => self::SPC_PRICING_2025['early_bird'],
                'phase_name' => 'Early Bird',
                'deadline' => $adjustedDates['early_bird_end']
            ];
        }
        
        if ($now <= \Carbon\Carbon::parse($adjustedDates['regular_registration_end'])) {
            return [
                'phase' => 'regular',
                'price' => self::SPC_PRICING_2025['regular'],
                'phase_name' => 'Regular Registration',
                'deadline' => $adjustedDates['regular_registration_end']
            ];
        }
        
        return [
            'phase' => 'closed',
            'price' => 0,
            'phase_name' => 'Pendaftaran Ditutup',
            'deadline' => null
        ];
    }

    /**
     * Get KDBI rules
     *
     * @return array
     */
    public function getKdbiRules()
    {
        return self::KDBI_RULES_2025;
    }

    /**
     * Get SPC rules
     *
     * @return array
     */
    public function getSpcRules()
    {
        return self::SPC_RULES_2025;
    }

    /**
     * Get SPC research fields
     *
     * @return array
     */
    public function getSpcResearchFields()
    {
        return self::SPC_RESEARCH_FIELDS;
    }

    /**
     * DCC UNAS FEST 2025 Timeline Constants
     * Based on official documents: DESKRIPSI DCC.pdf
     * 
     * DCC terdiri dari dua cabang lomba: Short Video dan Infographics
     * - 15 tim per cabang melalui tahapan penyisihan
     * - 15 tim lolos semifinal → 7 tim terbaik ke final
     * - Final dinilai online, awarding onsite
     */
    const DCC_TIMELINE_2025 = [
        // Registration Phases (sesuai dokumen PDF)
        'early_bird_start' => '2025-08-01',      // Early Bird Registration Start
        'early_bird_end' => '2025-08-31',        // Early Bird Registration End
        'phase_1_start' => '2025-09-01',         // Phase 1 Registration Start
        'phase_1_end' => '2025-09-15',           // Phase 1 Registration End
        'phase_2_start' => '2025-09-16',         // Phase 2 Registration Start
        'phase_2_end' => '2025-09-30',           // Phase 2 Registration End (Final Registration)
        
        // Competition Events
        'webinar_start' => '2025-10-01',         // Webinar untuk pengembangan kapasitas peserta
        'webinar_end' => '2025-10-03',           // Webinar selesai
        'submission_start' => '2025-10-04',      // Mulai pengumpulan karya
        'submission_deadline' => '2025-10-20',   // Batas akhir pengumpulan karya
        
        // Judging Phases
        'preliminary_judging_start' => '2025-10-21',    // Tahap penyisihan (15 tim)
        'preliminary_judging_end' => '2025-10-25',      // Penyisihan selesai
        'preliminary_announcement' => '2025-10-26',     // Pengumuman lolos semifinal
        
        'semifinal_judging_start' => '2025-10-27',      // Tahap semifinal (15 tim → 7 tim)
        'semifinal_judging_end' => '2025-10-31',        // Semifinal selesai
        'semifinal_announcement' => '2025-11-01',       // Pengumuman lolos final
        
        'final_judging_start' => '2025-11-02',          // Final judging (7 tim, dinilai online)
        'final_judging_end' => '2025-11-05',            // Final judging selesai
        
        // Award Event
        'awarding_ceremony' => '2025-11-10',            // Awarding ceremony (onsite)
    ];

    /**
     * Get adjusted DCC timeline for current year
     * 
     * @param int|null $year Override year (default: current year)
     * @return array
     */
    public static function getAdjustedDccTimeline($year = null)
    {
        $targetYear = $year ?? now()->year;
        $adjustedTimeline = [];
        
        foreach (self::DCC_TIMELINE_2025 as $key => $date) {
            $carbonDate = \Carbon\Carbon::parse($date);
            $adjustedDate = $carbonDate->setYear($targetYear);
            $adjustedTimeline[$key] = $adjustedDate->format('Y-m-d');
        }
        
        return $adjustedTimeline;
    }

    /**
     * DCC Pricing Constants sesuai BIAYA PENDAFTARAN DCC.pdf
     */
    const DCC_PRICING_2025 = [
        'early_bird' => 50000,    // Rp.50.000 (Early Bird)
        'phase_1' => 65000,       // Rp.65.000 (Fase 1)
        'phase_2' => 75000,       // Rp.75.000 (Fase 2)
    ];

    /**
     * DCC Competition Rules sesuai SYARAT DAN KETENTUAN LOMBA.pdf
     */
    const DCC_RULES_2025 = [
        // INFOGRAFIS Rules
        'infografis_rules' => [
            'Peserta Digital Content Competition UNAS FEST 2025 wajib menyertakan surat keterangan siswa/i aktif yang dikeluarkan oleh pihak sekolah SMA/MA/SMK sederajat di JABODETABEK',
            'Peserta Digital Content Competition UNAS FEST 2025 bersifat kelompok yang terdiri dari 3 orang',
            'Peserta Digital Content Competition UNAS FEST 2025 wajib melakukan pendaftaran di web yang telah disediakan oleh panitia',
            'Peserta Digital Content Competition UNAS FEST 2025 wajib mengikuti seluruh prosedur dan persyaratan yang telah ditentukan oleh panitia',
            'Peserta Digital Content Competition UNAS FEST 2025 wajib mengikuti batas waktu pengerjaan karya short video dan infographic yang telah ditentukan oleh panitia',
            'Peserta Digital Content Competition UNAS FEST 2025 wajib mengikuti tema lomba yang telah ditetapkan oleh panitia',
            'Karya yang diunggah harus merupakan hasil ciptaan asli peserta, bukan hasil plagiarisme, dan belum pernah diikutsertakan atau dipublikasikan dalam kompetisi lain',
            'Karya tidak diperkenankan mengandung unsur SARA (Suku, Agama, Ras, dan Antargolongan), kekerasan, pornografi, ujaran kebencian, kata kata kasar, maupun konten lain yang bertentangan dengan norma, etika dan peraturan perundang-undangan yang berlaku di Indonesia',
            'Peserta diwajibkan mengunggah karya melalui platform media sosial yang telah ditentukan (Youtube, Instagram, atau Tiktok) dengan menyertakan tagar resmi lomba dan mention akun resmi UNAS FEST',
            'Akun media sosial yang digunakan peserta untuk mengunggah hasil karya wajib bersifat publik (tidak dalam keadaan privat) selama periode kompetisi berlangsung',
            'Panitia penyelenggara berhak melakukan diskualifikasi terhadap peserta yang tidak mematuhi persyaratan dan terbukti melakukan kecurangan dalam bentuk apa pun',
            'Keputusan dewan juri bersifat final, mengikat, dan tidak dapat diganggu gugat',
            'Peserta dibebaskan untuk menggunakan software desain grafis apapun, seperti Freehand, Corel Draw, Adobe Photoshop, Canva atau aplikasi serupa dengan ketentuan tidak diperbolehkan menggunakan aplikasi berbasis kecerdasan buatan (AI)',
            'Kualitas desain infographic wajib memiliki resolusi full HD untuk memastikan ketajaman gambar yang optimal serta kesesuaian dengan ketentuan platform yang digunakan',
            'Karya infographic harus memadukan elemen teks, grafik, ilustrasi, dan ikon yang saling mendukung guna menyampaikan informasi secara jelas, sistematis, dan efektif',
            'Ukuran desain infographic disarankan dalam rasio 4 : 5 (potrait) untuk optimalisasi tampilan di media sosial'
        ],
        
        // SHORT VIDEO Rules
        'short_video_rules' => [
            'Peserta Digital Content Competition UNAS FEST 2025 wajib menyertakan surat keterangan siswa/i aktif yang dikeluarkan oleh pihak sekolah SMA/MA/SMK sederajat di JABODETABEK',
            'Peserta Digital Content Competition UNAS FEST 2025 merupakan siswa/i aktif SMA/MA/SMK sederajat di JABODETABEK',
            'Peserta Digital Content Competition UNAS FEST 2025 bersifat kelompok yang terdiri dari 3 orang',
            'Peserta Digital Content Competition UNAS FEST 2025 wajib melakukan pendaftaran di web yang telah disediakan oleh panitia',
            'Peserta Digital Content Competition UNAS FEST 2025 wajib mengikuti seluruh prosedur dan persyaratan yang telah ditentukan oleh panitia',
            'Peserta Digital Content Competition UNAS FEST 2025 wajib mengikuti batas waktu pengerjaan karya short video dan infographic yang telah ditentukan oleh panitia',
            'Peserta Digital Content Competition UNAS FEST 2025 wajib mengikuti tema lomba yang telah ditetapkan oleh panitia',
            'Karya short video berdurasi maksimum 3 (tiga) menit',
            'Karya yang diunggah harus merupakan hasil ciptaan asli peserta, bukan hasil plagiarisme, dan belum pernah diikutsertakan atau dipublikasikan dalam kompetisi lain',
            'Karya tidak diperkenankan mengandung unsur SARA (Suku, Agama, Ras, dan Antargolongan), kekerasan, pornografi, ujaran kebencian, kata kata kasar, maupun konten lain yang bertentangan dengan norma, etika dan peraturan perundang-undangan yang berlaku di Indonesia',
            'Peserta diwajibkan mengunggah karya melalui platform media sosial yang telah ditentukan (Youtube, Instagram, atau Tiktok) dengan menyertakan tagar resmi lomba dan mention akun resmi UNAS FEST',
            'Akun media sosial yang digunakan peserta untuk mengunggah hasil karya wajib bersifat publik (tidak dalam keadaan privat) selama periode kompetisi berlangsung',
            'Panitia penyelenggara berhak melakukan diskualifikasi terhadap peserta yang tidak mematuhi persyaratan dan terbukti melakukan kecurangan dalam bentuk apa pun',
            'Keputusan dewan juri bersifat final, mengikat, dan tidak dapat diganggu gugat',
            'Peserta dibebaskan untuk menggunakan software desain grafis apapun, seperti Freehand, Corel Draw, Adobe Photoshop, Canva atau aplikasi serupa dengan ketentuan tidak diperbolehkan menggunakan aplikasi berbasis kecerdasan buatan (AI)'
        ]
    ];

    /**
     * Get current DCC pricing phase
     *
     * @return array
     */
    public function getCurrentDccPricing()
    {
        $now = now();
        $adjustedDates = self::getAdjustedDccTimeline();
        
        if ($now <= \Carbon\Carbon::parse($adjustedDates['early_bird_end'])) {
            return [
                'phase' => 'early_bird',
                'price' => self::DCC_PRICING_2025['early_bird'],
                'phase_name' => 'Early Bird',
                'deadline' => $adjustedDates['early_bird_end']
            ];
        }
        
        if ($now <= \Carbon\Carbon::parse($adjustedDates['phase_1_end'])) {
            return [
                'phase' => 'phase_1',
                'price' => self::DCC_PRICING_2025['phase_1'],
                'phase_name' => 'Fase 1',
                'deadline' => $adjustedDates['phase_1_end']
            ];
        }
        
        if ($now <= \Carbon\Carbon::parse($adjustedDates['phase_2_end'])) {
            return [
                'phase' => 'phase_2',
                'price' => self::DCC_PRICING_2025['phase_2'],
                'phase_name' => 'Fase 2',
                'deadline' => $adjustedDates['phase_2_end']
            ];
        }
        
        return [
            'phase' => 'closed',
            'price' => 0,
            'phase_name' => 'Pendaftaran Ditutup',
            'deadline' => null
        ];
    }

    /**
     * Get DCC specific timeline sesuai dokumen
     *
     * @return array
     */
    public function getDccTimeline()
    {
        // Use adjusted timeline for current/appropriate year
        $adjustedDates = self::getAdjustedDccTimeline();
        
        $timeline = [
            [
                'title' => 'Early Bird Registration',
                'description' => 'Rp.50.000 - Pendaftaran tahap awal',
                'date' => \Carbon\Carbon::parse($adjustedDates['early_bird_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['early_bird_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['early_bird_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['early_bird_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-calendar-plus',
                'color' => 'success'
            ],
            [
                'title' => 'Fase 1 Registration',
                'description' => 'Rp.65.000 - Pendaftaran fase 1',
                'date' => \Carbon\Carbon::parse($adjustedDates['phase_1_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['phase_1_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['phase_1_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['phase_1_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-calendar-check',
                'color' => 'warning'
            ],
            [
                'title' => 'Fase 2 Registration',
                'description' => 'Rp.75.000 - Pendaftaran fase terakhir',
                'date' => \Carbon\Carbon::parse($adjustedDates['phase_2_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['phase_2_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['phase_2_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['phase_2_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-calendar-x',
                'color' => 'danger'
            ],
            [
                'title' => 'Webinar Pengembangan Kapasitas',
                'description' => 'Kegiatan webinar untuk memberikan pemahaman, keterampilan teknis, serta arahan dalam proses pembuatan karya',
                'date' => \Carbon\Carbon::parse($adjustedDates['webinar_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['webinar_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['webinar_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['webinar_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-camera-video',
                'color' => 'info'
            ],
            [
                'title' => 'Periode Pengumpulan Karya',
                'description' => 'Periode untuk mengumpulkan karya Short Video atau Infografis',
                'date' => \Carbon\Carbon::parse($adjustedDates['submission_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['submission_deadline']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['submission_deadline']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['submission_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-upload',
                'color' => 'primary'
            ],
            [
                'title' => 'Tahap Penyisihan',
                'description' => '15 tim per cabang lomba dinilai untuk lolos ke semifinal',
                'date' => \Carbon\Carbon::parse($adjustedDates['preliminary_judging_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['preliminary_judging_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['preliminary_judging_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['preliminary_judging_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-trophy',
                'color' => 'primary'
            ],
            [
                'title' => 'Pengumuman Lolos Semifinal',
                'description' => '15 tim lolos semifinal untuk masing-masing cabang lomba',
                'date' => \Carbon\Carbon::parse($adjustedDates['preliminary_announcement']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['preliminary_announcement']) ? 'completed' : 'upcoming',
                'icon' => 'bi-megaphone',
                'color' => 'info'
            ],
            [
                'title' => 'Tahap Semifinal',
                'description' => '15 tim dipilih 7 tim terbaik untuk melaju ke babak final',
                'date' => \Carbon\Carbon::parse($adjustedDates['semifinal_judging_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['semifinal_judging_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['semifinal_judging_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['semifinal_judging_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-award',
                'color' => 'info'
            ],
            [
                'title' => 'Pengumuman Lolos Final',
                'description' => '7 tim terbaik lolos ke babak final',
                'date' => \Carbon\Carbon::parse($adjustedDates['semifinal_announcement']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['semifinal_announcement']) ? 'completed' : 'upcoming',
                'icon' => 'bi-megaphone',
                'color' => 'warning'
            ],
            [
                'title' => 'Final Judging (Online)',
                'description' => '7 tim finalis dinilai secara online',
                'date' => \Carbon\Carbon::parse($adjustedDates['final_judging_start']),
                'end_date' => \Carbon\Carbon::parse($adjustedDates['final_judging_end']),
                'status' => now() > \Carbon\Carbon::parse($adjustedDates['final_judging_end']) ? 'completed' : 
                           (now() >= \Carbon\Carbon::parse($adjustedDates['final_judging_start']) ? 'ongoing' : 'upcoming'),
                'icon' => 'bi-trophy-fill',
                'color' => 'danger'
            ],
            [
                'title' => 'Awarding Ceremony (Onsite)',
                'description' => 'Acara pemberian penghargaan secara onsite',
                'date' => \Carbon\Carbon::parse($adjustedDates['awarding_ceremony']),
                'status' => now() >= \Carbon\Carbon::parse($adjustedDates['awarding_ceremony']) ? 'completed' : 'upcoming',
                'icon' => 'bi-trophy-fill',
                'color' => 'success'
            ]
        ];

        return $timeline;
    }

    /**
     * Check if this is DCC competition
     *
     * @return bool
     */
    public function isDccCompetition()
    {
        return $this->category === 'event_dcc';
    }

    /**
     * Get DCC rules
     *
     * @return array
     */
    public function getDccRules()
    {
        return self::DCC_RULES_2025;
    }
}
