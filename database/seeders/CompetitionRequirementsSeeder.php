<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\CompetitionRequirement;
use App\Models\CompetitionCriteria;
use App\Models\CompetitionJudge;
use App\Models\User;

class CompetitionRequirementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing competitions
        $kdbiCompetition = Competition::where('slug', 'kdbi-2025')->first();
        $edcCompetition = Competition::where('slug', 'edc-2025')->first();
        $shortMovieCompetition = Competition::where('slug', 'short-movie-2025')->first();
        $fotografiCompetition = Competition::where('slug', 'fotografi-2025')->first();
        $lktiCompetition = Competition::where('slug', 'karya-ilmiah-2025')->first();

        // KDBI Requirements
        if ($kdbiCompetition) {
            $this->seedKDBIRequirements($kdbiCompetition);
            $this->seedKDBICriteria($kdbiCompetition);
        }

        // EDC Requirements
        if ($edcCompetition) {
            $this->seedEDCRequirements($edcCompetition);
            $this->seedEDCCriteria($edcCompetition);
        }

        // Short Movie Requirements
        if ($shortMovieCompetition) {
            $this->seedDCCRequirements($shortMovieCompetition);
            $this->seedDCCCriteria($shortMovieCompetition);
        }

        // Fotografi Requirements
        if ($fotografiCompetition) {
            $this->seedFotografiRequirements($fotografiCompetition);
            $this->seedFotografiCriteria($fotografiCompetition);
        }

        // LKTI Requirements
        if ($lktiCompetition) {
            $this->seedLKTIRequirements($lktiCompetition);
            $this->seedLKTICriteria($lktiCompetition);
        }
    }
    
    private function createSampleCompetitions()
    {
        $competitions = [
            [
                'name' => 'Kompetisi Debat Bahasa Indonesia 2025',
                'slug' => 'kdbi-2025',
                'description' => 'Kompetisi debat bahasa Indonesia tingkat nasional',
                'category' => 'event_debate',
                'price' => 200000,
                'price_unas_student' => 150000,
                'price_external_student' => 200000,
                'registration_start' => now(),
                'registration_end' => now()->addMonths(2),
                'competition_start' => now()->addMonths(2)->addDays(7),
                'competition_end' => now()->addMonths(2)->addDays(10),
                'is_team_competition' => true,
                'min_team_members' => 3,
                'max_team_members' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Digital Content Competition 2025',
                'slug' => 'dcc-2025',
                'description' => 'Kompetisi konten digital kreatif',
                'category' => 'event_dcc',
                'price' => 150000,
                'price_unas_student' => 100000,
                'price_external_student' => 150000,
                'registration_start' => now(),
                'registration_end' => now()->addMonths(2),
                'competition_start' => now()->addMonths(2)->addDays(7),
                'competition_end' => now()->addMonths(2)->addDays(10),
                'is_team_competition' => true,
                'min_team_members' => 2,
                'max_team_members' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Lomba Karya Tulis Ilmiah 2025',
                'slug' => 'lkti-2025',
                'description' => 'Lomba karya tulis ilmiah tingkat nasional',
                'category' => 'event_scientific_paper',
                'price' => 100000,
                'price_unas_student' => 75000,
                'price_external_student' => 100000,
                'registration_start' => now(),
                'registration_end' => now()->addMonths(2),
                'competition_start' => now()->addMonths(2)->addDays(7),
                'competition_end' => now()->addMonths(2)->addDays(10),
                'is_team_competition' => true,
                'min_team_members' => 2,
                'max_team_members' => 3,
                'is_active' => true,
            ],
        ];
        
        foreach ($competitions as $competitionData) {
            Competition::create($competitionData);
        }
    }
    
    private function seedKDBIRequirements($competition)
    {
        $requirements = [
            [
                'field_name' => 'school_certificate',
                'field_type' => 'file',
                'field_label' => 'Surat Keterangan Sekolah/Universitas',
                'help_text' => 'Upload surat keterangan dari sekolah/universitas yang masih berlaku',
                'is_required' => true,
                'validation_rules' => json_encode([
                    'mimes' => ['pdf', 'jpg', 'png'],
                    'max_size' => 2048
                ]),
                'field_group' => 'documents',
                'order_index' => 1
            ],
            [
                'field_name' => 'student_id_card',
                'field_type' => 'file',
                'field_label' => 'Kartu Tanda Mahasiswa/Siswa',
                'help_text' => 'Upload foto KTM/Kartu Pelajar yang masih berlaku',
                'is_required' => true,
                'validation_rules' => json_encode([
                    'mimes' => ['pdf', 'jpg', 'png'],
                    'max_size' => 2048
                ]),
                'field_group' => 'documents',
                'order_index' => 2
            ],
            [
                'field_name' => 'debate_experience',
                'field_type' => 'select',
                'field_label' => 'Pengalaman Debat',
                'help_text' => 'Pilih tingkat pengalaman debat Anda',
                'is_required' => true,
                'field_options' => json_encode([
                    'beginner' => 'Pemula (0-1 tahun)',
                    'intermediate' => 'Menengah (2-3 tahun)',
                    'advanced' => 'Lanjutan (4+ tahun)'
                ]),
                'field_group' => 'experience',
                'order_index' => 3
            ],
            [
                'field_name' => 'previous_achievements',
                'field_type' => 'textarea',
                'field_label' => 'Prestasi Debat Sebelumnya',
                'help_text' => 'Jelaskan prestasi debat yang pernah diraih (opsional)',
                'is_required' => false,
                'validation_rules' => json_encode([
                    'max_length' => 1000
                ]),
                'field_group' => 'experience',
                'order_index' => 4
            ]
        ];
        
        foreach ($requirements as $requirement) {
            $requirement['competition_id'] = $competition->id;
            CompetitionRequirement::create($requirement);
        }
    }

    private function seedEDCRequirements($competition)
    {
        $requirements = [
            [
                'field_name' => 'english_proficiency',
                'field_type' => 'select',
                'field_label' => 'English Proficiency Level',
                'help_text' => 'Select your English proficiency level',
                'is_required' => true,
                'field_options' => json_encode([
                    'intermediate' => 'Intermediate',
                    'upper_intermediate' => 'Upper Intermediate',
                    'advanced' => 'Advanced',
                    'proficient' => 'Proficient'
                ]),
                'field_group' => 'basic',
                'order_index' => 1
            ],
            [
                'field_name' => 'debate_experience',
                'field_type' => 'textarea',
                'field_label' => 'Debate Experience',
                'help_text' => 'Describe your previous debate experience (competitions, training, etc.)',
                'is_required' => true,
                'validation_rules' => json_encode([
                    'max_length' => 1000
                ]),
                'field_group' => 'experience',
                'order_index' => 2
            ],
            [
                'field_name' => 'preferred_position',
                'field_type' => 'radio',
                'field_label' => 'Preferred Debate Position',
                'help_text' => 'Select your preferred position in debate',
                'is_required' => true,
                'field_options' => json_encode([
                    'first_speaker' => 'First Speaker',
                    'second_speaker' => 'Second Speaker',
                    'third_speaker' => 'Third Speaker',
                    'flexible' => 'Flexible'
                ]),
                'field_group' => 'basic',
                'order_index' => 3
            ]
        ];

        foreach ($requirements as $requirement) {
            $requirement['competition_id'] = $competition->id;
            CompetitionRequirement::create($requirement);
        }
    }

    private function seedFotografiRequirements($competition)
    {
        $requirements = [
            [
                'field_name' => 'photography_category',
                'field_type' => 'select',
                'field_label' => 'Kategori Fotografi',
                'help_text' => 'Pilih kategori fotografi yang akan diikuti',
                'is_required' => true,
                'field_options' => json_encode([
                    'nature' => 'Alam dan Landscape',
                    'portrait' => 'Portrait dan Human Interest',
                    'street' => 'Street Photography',
                    'culture' => 'Budaya dan Tradisi',
                    'architecture' => 'Arsitektur dan Urban'
                ]),
                'field_group' => 'basic',
                'order_index' => 1
            ],
            [
                'field_name' => 'camera_equipment',
                'field_type' => 'text',
                'field_label' => 'Peralatan Kamera',
                'help_text' => 'Sebutkan kamera dan lensa yang akan digunakan',
                'is_required' => true,
                'validation_rules' => json_encode([
                    'max_length' => 200
                ]),
                'field_group' => 'technical',
                'order_index' => 2
            ],
            [
                'field_name' => 'photography_experience',
                'field_type' => 'textarea',
                'field_label' => 'Pengalaman Fotografi',
                'help_text' => 'Ceritakan pengalaman fotografi Anda (kompetisi, pameran, dll)',
                'is_required' => true,
                'validation_rules' => json_encode([
                    'max_length' => 1000
                ]),
                'field_group' => 'experience',
                'order_index' => 3
            ],
            [
                'field_name' => 'portfolio_link',
                'field_type' => 'url',
                'field_label' => 'Link Portofolio',
                'help_text' => 'Link ke portofolio fotografi Anda (Instagram, website, dll)',
                'is_required' => false,
                'field_group' => 'portfolio',
                'order_index' => 4
            ]
        ];

        foreach ($requirements as $requirement) {
            $requirement['competition_id'] = $competition->id;
            CompetitionRequirement::create($requirement);
        }
    }

    private function seedDCCRequirements($competition)
    {
        $requirements = [
            [
                'field_name' => 'portfolio_link',
                'field_type' => 'url',
                'field_label' => 'Link Portofolio',
                'help_text' => 'Link ke portofolio karya digital Anda (YouTube, Instagram, website, dll)',
                'is_required' => true,
                'field_group' => 'portfolio',
                'order_index' => 1
            ],
            [
                'field_name' => 'content_category',
                'field_type' => 'radio',
                'field_label' => 'Kategori Konten',
                'help_text' => 'Pilih kategori konten yang akan dibuat',
                'is_required' => true,
                'field_options' => json_encode([
                    'video' => 'Video/Film Pendek',
                    'animation' => 'Animasi',
                    'photography' => 'Fotografi Digital',
                    'design' => 'Desain Grafis'
                ]),
                'field_group' => 'content',
                'order_index' => 2
            ],
            [
                'field_name' => 'software_used',
                'field_type' => 'checkbox',
                'field_label' => 'Software yang Dikuasai',
                'help_text' => 'Pilih software yang Anda kuasai (boleh lebih dari satu)',
                'is_required' => true,
                'field_options' => json_encode([
                    'premiere' => 'Adobe Premiere Pro',
                    'after_effects' => 'Adobe After Effects',
                    'photoshop' => 'Adobe Photoshop',
                    'illustrator' => 'Adobe Illustrator',
                    'davinci' => 'DaVinci Resolve',
                    'blender' => 'Blender',
                    'other' => 'Lainnya'
                ]),
                'field_group' => 'technical',
                'order_index' => 3
            ]
        ];
        
        foreach ($requirements as $requirement) {
            $requirement['competition_id'] = $competition->id;
            CompetitionRequirement::create($requirement);
        }
    }
    
    private function seedLKTIRequirements($competition)
    {
        $requirements = [
            [
                'field_name' => 'research_field',
                'field_type' => 'select',
                'field_label' => 'Bidang Penelitian',
                'help_text' => 'Pilih bidang penelitian karya tulis ilmiah',
                'is_required' => true,
                'field_options' => json_encode([
                    'technology' => 'Teknologi dan Inovasi',
                    'health' => 'Kesehatan dan Kedokteran',
                    'environment' => 'Lingkungan dan Keberlanjutan',
                    'social' => 'Sosial dan Humaniora',
                    'economics' => 'Ekonomi dan Bisnis'
                ]),
                'field_group' => 'research',
                'order_index' => 1
            ],
            [
                'field_name' => 'research_title',
                'field_type' => 'text',
                'field_label' => 'Judul Penelitian',
                'help_text' => 'Masukkan judul lengkap karya tulis ilmiah',
                'is_required' => true,
                'validation_rules' => json_encode([
                    'max_length' => 200
                ]),
                'field_group' => 'research',
                'order_index' => 2
            ],
            [
                'field_name' => 'research_abstract',
                'field_type' => 'textarea',
                'field_label' => 'Abstrak Penelitian',
                'help_text' => 'Ringkasan penelitian maksimal 300 kata',
                'is_required' => true,
                'validation_rules' => json_encode([
                    'max_length' => 2000
                ]),
                'field_group' => 'research',
                'order_index' => 3
            ]
        ];
        
        foreach ($requirements as $requirement) {
            $requirement['competition_id'] = $competition->id;
            CompetitionRequirement::create($requirement);
        }
    }
    
    private function seedKDBICriteria($competition)
    {
        $criteria = [
            [
                'criteria_name' => 'Matter (Materi)',
                'description' => 'Kualitas dan kedalaman argumen yang disampaikan',
                'weight_percentage' => 40,
                'sub_criteria' => json_encode([
                    'argument_quality' => 'Kualitas Argumen',
                    'evidence_support' => 'Dukungan Bukti',
                    'logical_reasoning' => 'Penalaran Logis'
                ]),
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Manner (Cara Penyampaian)',
                'description' => 'Cara penyampaian dan etika dalam berdebat',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'speaking_style' => 'Gaya Berbicara',
                    'body_language' => 'Bahasa Tubuh',
                    'ethics' => 'Etika Debat'
                ]),
                'max_score' => 100,
                'order_index' => 2
            ],
            [
                'criteria_name' => 'Method (Metode)',
                'description' => 'Struktur dan strategi debat yang digunakan',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'structure' => 'Struktur Argumen',
                    'time_management' => 'Manajemen Waktu',
                    'rebuttal_strategy' => 'Strategi Bantahan'
                ]),
                'max_score' => 100,
                'order_index' => 3
            ]
        ];
        
        foreach ($criteria as $criterion) {
            $criterion['competition_id'] = $competition->id;
            CompetitionCriteria::create($criterion);
        }
    }

    private function seedEDCCriteria($competition)
    {
        $criteria = [
            [
                'criteria_name' => 'Content (Konten)',
                'description' => 'Kualitas argumen dan substansi materi',
                'weight_percentage' => 40,
                'sub_criteria' => json_encode([
                    'argument_quality' => 'Kualitas Argumen',
                    'evidence_support' => 'Dukungan Bukti',
                    'logical_reasoning' => 'Penalaran Logis'
                ]),
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Manner (Cara Penyampaian)',
                'description' => 'Gaya dan teknik penyampaian dalam bahasa Inggris',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'english_fluency' => 'Kelancaran Bahasa Inggris',
                    'pronunciation' => 'Pelafalan',
                    'confidence' => 'Kepercayaan Diri'
                ]),
                'max_score' => 100,
                'order_index' => 2
            ],
            [
                'criteria_name' => 'Method (Metode)',
                'description' => 'Struktur dan strategi debat yang digunakan',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'structure' => 'Struktur Argumen',
                    'time_management' => 'Manajemen Waktu',
                    'rebuttal_strategy' => 'Strategi Bantahan'
                ]),
                'max_score' => 100,
                'order_index' => 3
            ]
        ];

        foreach ($criteria as $criterion) {
            $criterion['competition_id'] = $competition->id;
            CompetitionCriteria::create($criterion);
        }
    }

    private function seedFotografiCriteria($competition)
    {
        $criteria = [
            [
                'criteria_name' => 'Komposisi dan Teknik',
                'description' => 'Kualitas komposisi dan teknik fotografi',
                'weight_percentage' => 35,
                'sub_criteria' => json_encode([
                    'composition' => 'Komposisi',
                    'lighting' => 'Pencahayaan',
                    'focus_sharpness' => 'Ketajaman Fokus'
                ]),
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Kreativitas dan Originalitas',
                'description' => 'Tingkat kreativitas dan keunikan karya',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'creativity' => 'Kreativitas',
                    'originality' => 'Originalitas',
                    'artistic_vision' => 'Visi Artistik'
                ]),
                'max_score' => 100,
                'order_index' => 2
            ],
            [
                'criteria_name' => 'Kesesuaian Tema',
                'description' => 'Kesesuaian dengan tema kompetisi',
                'weight_percentage' => 25,
                'sub_criteria' => json_encode([
                    'theme_relevance' => 'Relevansi Tema',
                    'message_delivery' => 'Penyampaian Pesan',
                    'cultural_value' => 'Nilai Budaya'
                ]),
                'max_score' => 100,
                'order_index' => 3
            ],
            [
                'criteria_name' => 'Impact dan Emosi',
                'description' => 'Dampak visual dan emosional foto',
                'weight_percentage' => 10,
                'sub_criteria' => json_encode([
                    'visual_impact' => 'Dampak Visual',
                    'emotional_connection' => 'Koneksi Emosional',
                    'storytelling' => 'Bercerita'
                ]),
                'max_score' => 100,
                'order_index' => 4
            ]
        ];

        foreach ($criteria as $criterion) {
            $criterion['competition_id'] = $competition->id;
            CompetitionCriteria::create($criterion);
        }
    }

    private function seedDCCCriteria($competition)
    {
        $criteria = [
            [
                'criteria_name' => 'Kreativitas',
                'description' => 'Tingkat inovasi dan keunikan konten',
                'weight_percentage' => 35,
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Kualitas Teknis',
                'description' => 'Kualitas produksi dan teknis konten',
                'weight_percentage' => 25,
                'max_score' => 100,
                'order_index' => 2
            ],
            [
                'criteria_name' => 'Pesan dan Konten',
                'description' => 'Kejelasan pesan dan relevansi konten',
                'weight_percentage' => 25,
                'max_score' => 100,
                'order_index' => 3
            ],
            [
                'criteria_name' => 'Dampak dan Engagement',
                'description' => 'Potensi dampak dan daya tarik konten',
                'weight_percentage' => 15,
                'max_score' => 100,
                'order_index' => 4
            ]
        ];
        
        foreach ($criteria as $criterion) {
            $criterion['competition_id'] = $competition->id;
            CompetitionCriteria::create($criterion);
        }
    }
    
    private function seedLKTICriteria($competition)
    {
        $criteria = [
            [
                'criteria_name' => 'Originalitas dan Inovasi',
                'description' => 'Kebaruan dan inovasi dalam penelitian',
                'weight_percentage' => 30,
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Metodologi Penelitian',
                'description' => 'Ketepatan dan kualitas metodologi yang digunakan',
                'weight_percentage' => 25,
                'max_score' => 100,
                'order_index' => 2
            ],
            [
                'criteria_name' => 'Analisis dan Pembahasan',
                'description' => 'Kedalaman analisis dan kualitas pembahasan',
                'weight_percentage' => 25,
                'max_score' => 100,
                'order_index' => 3
            ],
            [
                'criteria_name' => 'Sistematika dan Bahasa',
                'description' => 'Sistematika penulisan dan penggunaan bahasa',
                'weight_percentage' => 20,
                'max_score' => 100,
                'order_index' => 4
            ]
        ];
        
        foreach ($criteria as $criterion) {
            $criterion['competition_id'] = $competition->id;
            CompetitionCriteria::create($criterion);
        }
    }
}
