<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\CompetitionRequirement;
use App\Models\CompetitionCriteria;
use Carbon\Carbon;

class CompetitionDetailSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data with foreign key checks disabled
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CompetitionCriteria::truncate();
        CompetitionRequirement::truncate();
        Competition::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create competitions
        $this->createDCCCompetition();
        $this->createEDCCompetition();
        $this->createKDBICompetition();
        $this->createSPCCompetition();
        $this->createInfografisCompetition();
    }

    private function createDCCCompetition()
    {
        $competition = Competition::create([
            'name' => 'Digital Content Competition (DCC)',
            'slug' => 'dcc-2025',
            'description' => 'Kompetisi kreativitas digital yang menantang peserta untuk menciptakan konten digital inovatif dengan tema "Digital Innovation for Sustainable Future". Peserta dapat membuat video, animasi, infografis digital, atau desain grafis yang menginspirasi.',
            'category' => 'technology',
            'price' => 75000,
            'early_bird_price' => 60000,
            'early_bird_deadline' => Carbon::now()->addDays(30),
            'max_participants' => 150,
            'max_team_members' => 3,
            'min_team_members' => 1,
            'registration_start' => Carbon::create(2025, 2, 1),
            'registration_end' => Carbon::create(2025, 2, 28),
            'competition_start' => Carbon::create(2025, 3, 15),
            'competition_end' => Carbon::create(2025, 3, 17),
            'is_active' => true,
            'rules' => json_encode(['Peserta wajib mahasiswa aktif', 'Karya original', 'Mengikuti tema yang ditentukan', 'Mematuhi timeline yang ditetapkan']),
            'prizes' => json_encode([
                'first' => 'Rp 5.000.000 + Sertifikat + Trophy',
                'second' => 'Rp 3.000.000 + Sertifikat + Trophy',
                'third' => 'Rp 2.000.000 + Sertifikat + Trophy',
                'favorite' => 'Rp 1.000.000 + Sertifikat'
            ]),
            'contact_person_name' => 'Tim DCC UNAS Fest',
            'contact_person_whatsapp' => '+62 812-3456-7890'
        ]);

        $this->createDCCRequirements($competition);
        $this->createDCCCriteria($competition);
    }

    private function createEDCCompetition()
    {
        $competition = Competition::create([
            'name' => 'English Debate Competition (EDC)',
            'slug' => 'edc-2025',
            'description' => 'Kompetisi debat bahasa Inggris tingkat nasional yang menguji kemampuan argumentasi, public speaking, dan critical thinking peserta dalam bahasa Inggris. Format debat menggunakan Asian Parliamentary Debate.',
            'category' => 'health',
            'price' => 200000,
            'early_bird_price' => 150000,
            'early_bird_deadline' => Carbon::now()->addDays(25),
            'max_participants' => 64,
            'max_team_members' => 3,
            'min_team_members' => 3,
            'registration_start' => Carbon::create(2025, 1, 15),
            'registration_end' => Carbon::create(2025, 2, 15),
            'competition_start' => Carbon::create(2025, 3, 1),
            'competition_end' => Carbon::create(2025, 3, 3),
            'is_active' => true,
            'rules' => 'Tim terdiri dari 3 orang mahasiswa aktif, menggunakan format Asian Parliamentary, bahasa pengantar Inggris, dan mengikuti code of conduct debat.',
            'prizes' => json_encode([
                'champion' => 'Rp 7.500.000 + Trophy + Sertifikat',
                'runner_up' => 'Rp 5.000.000 + Trophy + Sertifikat',
                'semifinalist' => 'Rp 2.500.000 + Sertifikat',
                'best_speaker' => 'Rp 1.500.000 + Trophy + Sertifikat'
            ]),
            'contact_person_name' => 'Tim EDC UNAS Fest',
            'contact_person_whatsapp' => '+62 813-4567-8901'
        ]);

        $this->createEDCRequirements($competition);
        $this->createEDCCriteria($competition);
    }

    private function createKDBICompetition()
    {
        $competition = Competition::create([
            'name' => 'Kompetisi Debat Bahasa Indonesia (KDBI)',
            'slug' => 'kdbi-2025',
            'description' => 'Kompetisi debat bahasa Indonesia yang bertujuan mengasah kemampuan berargumentasi, berpikir kritis, dan public speaking peserta dalam bahasa Indonesia. Format debat menggunakan sistem 3 vs 3 dengan tema aktual.',
            'category' => 'biodiversity',
            'price' => 150000,
            'early_bird_price' => 100000,
            'early_bird_deadline' => Carbon::now()->addDays(20),
            'max_participants' => 48,
            'max_team_members' => 3,
            'min_team_members' => 3,
            'registration_start' => Carbon::create(2025, 3, 1),
            'registration_end' => Carbon::create(2025, 3, 15),
            'competition_start' => Carbon::create(2025, 3, 20),
            'competition_end' => Carbon::create(2025, 3, 28),
            'is_active' => true,
            'rules' => 'Tim terdiri dari 3 orang mahasiswa aktif, menggunakan bahasa Indonesia yang baik dan benar, mengikuti etika debat, dan mematuhi time management.',
            'prizes' => json_encode([
                'juara_1' => 'Rp 4.000.000 + Trophy + Sertifikat',
                'juara_2' => 'Rp 2.500.000 + Trophy + Sertifikat',
                'juara_3' => 'Rp 1.500.000 + Trophy + Sertifikat',
                'best_speaker' => 'Rp 1.000.000 + Trophy + Sertifikat'
            ]),
            'contact_person_name' => 'Tim KDBI UNAS Fest',
            'contact_person_whatsapp' => '+62 814-5678-9012'
        ]);

        $this->createKDBIRequirements($competition);
        $this->createKDBICriteria($competition);
    }

    private function createSPCCompetition()
    {
        $competition = Competition::create([
            'name' => 'Scientific Paper Competition (SPC)',
            'slug' => 'spc-2025',
            'description' => 'Kompetisi karya tulis ilmiah yang menantang mahasiswa untuk menghasilkan penelitian berkualitas dengan tema "Innovation and Technology for Sustainable Development". Peserta dapat memilih bidang teknologi, sosial, atau lingkungan.',
            'category' => 'technology',
            'price' => 100000,
            'early_bird_price' => 75000,
            'early_bird_deadline' => Carbon::now()->addDays(35),
            'max_participants' => 100,
            'max_team_members' => 3,
            'min_team_members' => 1,
            'registration_start' => Carbon::create(2025, 1, 1),
            'registration_end' => Carbon::create(2025, 3, 31),
            'competition_start' => Carbon::create(2025, 4, 15),
            'competition_end' => Carbon::create(2025, 4, 17),
            'is_active' => true,
            'rules' => 'Karya tulis original, mengikuti format ilmiah yang ditentukan, maksimal 15 halaman, dan belum pernah dipublikasikan sebelumnya.',
            'prizes' => json_encode([
                'juara_1' => 'Rp 6.000.000 + Trophy + Sertifikat + Publikasi Jurnal',
                'juara_2' => 'Rp 4.000.000 + Trophy + Sertifikat',
                'juara_3' => 'Rp 2.500.000 + Trophy + Sertifikat',
                'best_innovation' => 'Rp 1.500.000 + Sertifikat'
            ]),
            'contact_person_name' => 'Tim SPC UNAS Fest',
            'contact_person_whatsapp' => '+62 815-6789-0123'
        ]);

        $this->createSPCRequirements($competition);
        $this->createSPCCriteria($competition);
    }

    private function createInfografisCompetition()
    {
        $competition = Competition::create([
            'name' => 'Kompetisi Infografis',
            'slug' => 'infografis-2025',
            'description' => 'Kompetisi desain infografis yang menantang peserta untuk menyajikan informasi kompleks dalam bentuk visual yang menarik dan mudah dipahami. Tema: "Data Visualization for Better Understanding".',
            'category' => 'health',
            'price' => 75000,
            'early_bird_price' => 60000,
            'early_bird_deadline' => Carbon::now()->addDays(30),
            'max_participants' => 100,
            'max_team_members' => 2,
            'min_team_members' => 1,
            'registration_start' => Carbon::create(2025, 2, 15),
            'registration_end' => Carbon::create(2025, 3, 15),
            'competition_start' => Carbon::create(2025, 4, 1),
            'competition_end' => Carbon::create(2025, 4, 3),
            'is_active' => true,
            'rules' => 'Desain original, format digital (PNG/JPG), resolusi minimal 300 DPI, dan mengikuti tema yang ditentukan.',
            'prizes' => json_encode([
                'juara_1' => 'Rp 3.000.000 + Trophy + Sertifikat',
                'juara_2' => 'Rp 2.000.000 + Trophy + Sertifikat',
                'juara_3' => 'Rp 1.000.000 + Trophy + Sertifikat',
                'people_choice' => 'Rp 500.000 + Sertifikat'
            ]),
            'contact_person_name' => 'Tim Infografis UNAS Fest',
            'contact_person_whatsapp' => '+62 816-7890-1234'
        ]);

        $this->createInfografisRequirements($competition);
        $this->createInfografisCriteria($competition);
    }

    // DCC Requirements and Criteria
    private function createDCCRequirements($competition)
    {
        $requirements = [
            [
                'field_name' => 'team_leader_name',
                'field_type' => 'text',
                'field_label' => 'Nama Lengkap Ketua Tim',
                'help_text' => 'Masukkan nama lengkap ketua tim sesuai KTM',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_leader',
                'order_index' => 1
            ],
            [
                'field_name' => 'team_leader_nim',
                'field_type' => 'text',
                'field_label' => 'NIM/NPM Ketua Tim',
                'help_text' => 'Nomor Induk Mahasiswa ketua tim',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 20]),
                'field_group' => 'team_leader',
                'order_index' => 2
            ],
            [
                'field_name' => 'team_leader_university',
                'field_type' => 'text',
                'field_label' => 'Universitas/Institusi',
                'help_text' => 'Nama universitas atau institusi ketua tim',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_leader',
                'order_index' => 3
            ],
            [
                'field_name' => 'team_leader_faculty',
                'field_type' => 'text',
                'field_label' => 'Fakultas/Jurusan',
                'help_text' => 'Fakultas dan jurusan ketua tim',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_leader',
                'order_index' => 4
            ],
            [
                'field_name' => 'content_category',
                'field_type' => 'select',
                'field_label' => 'Kategori Konten',
                'help_text' => 'Pilih kategori konten digital yang akan dibuat',
                'is_required' => true,
                'field_options' => json_encode([
                    'video' => 'Video/Film Pendek',
                    'animation' => 'Animasi',
                    'infographic' => 'Infografis Digital',
                    'design' => 'Desain Grafis'
                ]),
                'field_group' => 'competition',
                'order_index' => 5
            ],
            [
                'field_name' => 'portfolio_link',
                'field_type' => 'url',
                'field_label' => 'Link Portfolio (Opsional)',
                'help_text' => 'Link ke portfolio karya sebelumnya (Google Drive, Behance, dll)',
                'is_required' => false,
                'validation_rules' => json_encode(['url' => true]),
                'field_group' => 'additional',
                'order_index' => 6
            ],
            [
                'field_name' => 'team_cv',
                'field_type' => 'file',
                'field_label' => 'CV Tim',
                'help_text' => 'Upload CV singkat tim (PDF, maksimal 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['pdf'], 'max_size' => 2048]),
                'field_group' => 'documents',
                'order_index' => 7
            ],
            [
                'field_name' => 'ktm_files',
                'field_type' => 'file',
                'field_label' => 'KTM Semua Anggota',
                'help_text' => 'Upload KTM semua anggota tim dalam satu file PDF',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['pdf'], 'max_size' => 5120]),
                'field_group' => 'documents',
                'order_index' => 8
            ],
            [
                'field_name' => 'originality_statement',
                'field_type' => 'file',
                'field_label' => 'Surat Pernyataan Orisinalitas',
                'help_text' => 'Upload surat pernyataan orisinalitas yang telah ditandatangani',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['pdf'], 'max_size' => 2048]),
                'field_group' => 'documents',
                'order_index' => 9
            ]
        ];

        foreach ($requirements as $requirement) {
            CompetitionRequirement::create(array_merge($requirement, [
                'competition_id' => $competition->id
            ]));
        }
    }

    private function createDCCCriteria($competition)
    {
        $criteria = [
            [
                'criteria_name' => 'Kreativitas dan Originalitas',
                'description' => 'Penilaian terhadap tingkat kreativitas, originalitas ide, dan inovasi dalam eksekusi',
                'weight_percentage' => 25,
                'sub_criteria' => json_encode([
                    'originality' => 'Originalitas Ide',
                    'innovation' => 'Inovasi Eksekusi',
                    'uniqueness' => 'Keunikan Karya'
                ]),
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Kualitas Teknis',
                'description' => 'Penilaian terhadap kualitas teknis produksi, visual/audio, dan penggunaan tools',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'visual_quality' => 'Kualitas Visual/Audio',
                    'production_technique' => 'Teknik Produksi',
                    'tool_usage' => 'Penggunaan Tools'
                ]),
                'max_score' => 100,
                'order_index' => 2
            ],
            [
                'criteria_name' => 'Relevansi dan Pesan',
                'description' => 'Kesesuaian dengan tema, target audience, dan penyampaian pesan',
                'weight_percentage' => 25,
                'sub_criteria' => json_encode([
                    'theme_relevance' => 'Kesesuaian Tema',
                    'target_audience' => 'Target Audience',
                    'message_delivery' => 'Penyampaian Pesan'
                ]),
                'max_score' => 100,
                'order_index' => 3
            ],
            [
                'criteria_name' => 'Presentasi dan Storytelling',
                'description' => 'Kemampuan presentasi, storytelling, dan engagement dengan audience',
                'weight_percentage' => 20,
                'sub_criteria' => json_encode([
                    'presentation' => 'Cara Penyampaian',
                    'storytelling' => 'Storytelling',
                    'engagement' => 'Engagement'
                ]),
                'max_score' => 100,
                'order_index' => 4
            ]
        ];

        foreach ($criteria as $criterion) {
            CompetitionCriteria::create(array_merge($criterion, [
                'competition_id' => $competition->id
            ]));
        }
    }

    // EDC Requirements and Criteria
    private function createEDCRequirements($competition)
    {
        $requirements = [
            [
                'field_name' => 'team_name',
                'field_type' => 'text',
                'field_label' => 'Nama Tim',
                'help_text' => 'Nama tim untuk kompetisi debat',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 50]),
                'field_group' => 'team_info',
                'order_index' => 1
            ],
            [
                'field_name' => 'university_name',
                'field_type' => 'text',
                'field_label' => 'Nama Universitas',
                'help_text' => 'Nama universitas yang diwakili',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_info',
                'order_index' => 2
            ],
            [
                'field_name' => 'debate_experience',
                'field_type' => 'textarea',
                'field_label' => 'Pengalaman Debat Tim',
                'help_text' => 'Ceritakan pengalaman debat anggota tim (kompetisi, prestasi, dll)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 1000]),
                'field_group' => 'experience',
                'order_index' => 3
            ],
            [
                'field_name' => 'english_proficiency',
                'field_type' => 'select',
                'field_label' => 'Tingkat Kemampuan Bahasa Inggris',
                'help_text' => 'Pilih tingkat kemampuan bahasa Inggris tim',
                'is_required' => true,
                'field_options' => json_encode([
                    'intermediate' => 'Intermediate',
                    'upper_intermediate' => 'Upper Intermediate',
                    'advanced' => 'Advanced',
                    'proficient' => 'Proficient'
                ]),
                'field_group' => 'skills',
                'order_index' => 4
            ],
            [
                'field_name' => 'team_photo',
                'field_type' => 'file',
                'field_label' => 'Foto Tim',
                'help_text' => 'Upload foto tim formal (JPG/PNG, maksimal 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'documents',
                'order_index' => 5
            ],
            [
                'field_name' => 'commitment_letter',
                'field_type' => 'file',
                'field_label' => 'Surat Komitmen',
                'help_text' => 'Upload surat komitmen mengikuti kompetisi hingga selesai',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['pdf'], 'max_size' => 2048]),
                'field_group' => 'documents',
                'order_index' => 6
            ]
        ];

        foreach ($requirements as $requirement) {
            CompetitionRequirement::create(array_merge($requirement, [
                'competition_id' => $competition->id
            ]));
        }
    }

    private function createEDCCriteria($competition)
    {
        $criteria = [
            [
                'criteria_name' => 'Content and Arguments',
                'description' => 'Kualitas konten, kekuatan argumen, dan relevansi dengan mosi',
                'weight_percentage' => 40,
                'sub_criteria' => json_encode([
                    'argument_strength' => 'Kekuatan Argumen',
                    'evidence_quality' => 'Kualitas Bukti',
                    'relevance' => 'Relevansi dengan Mosi'
                ]),
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Style and Delivery',
                'description' => 'Gaya penyampaian, bahasa tubuh, dan kemampuan public speaking',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'speaking_style' => 'Gaya Berbicara',
                    'body_language' => 'Bahasa Tubuh',
                    'voice_projection' => 'Proyeksi Suara'
                ]),
                'max_score' => 100,
                'order_index' => 2
            ],
            [
                'criteria_name' => 'Strategy and Structure',
                'description' => 'Strategi debat, struktur argumen, dan manajemen waktu',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'debate_strategy' => 'Strategi Debat',
                    'argument_structure' => 'Struktur Argumen',
                    'time_management' => 'Manajemen Waktu'
                ]),
                'max_score' => 100,
                'order_index' => 3
            ]
        ];

        foreach ($criteria as $criterion) {
            CompetitionCriteria::create(array_merge($criterion, [
                'competition_id' => $competition->id
            ]));
        }
    }

    // KDBI Requirements and Criteria
    private function createKDBIRequirements($competition)
    {
        $requirements = [
            [
                'field_name' => 'team_name',
                'field_type' => 'text',
                'field_label' => 'Nama Tim',
                'help_text' => 'Nama tim untuk kompetisi debat bahasa Indonesia',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 50]),
                'field_group' => 'team_info',
                'order_index' => 1
            ],
            [
                'field_name' => 'university_name',
                'field_type' => 'text',
                'field_label' => 'Nama Universitas',
                'help_text' => 'Nama universitas yang diwakili',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_info',
                'order_index' => 2
            ],
            [
                'field_name' => 'debate_experience_id',
                'field_type' => 'textarea',
                'field_label' => 'Pengalaman Debat Bahasa Indonesia',
                'help_text' => 'Ceritakan pengalaman debat bahasa Indonesia anggota tim',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 1000]),
                'field_group' => 'experience',
                'order_index' => 3
            ],
            [
                'field_name' => 'motivation_letter',
                'field_type' => 'textarea',
                'field_label' => 'Surat Motivasi',
                'help_text' => 'Tuliskan motivasi tim mengikuti KDBI UNAS Fest 2025',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 500]),
                'field_group' => 'motivation',
                'order_index' => 4
            ],
            [
                'field_name' => 'team_photo',
                'field_type' => 'file',
                'field_label' => 'Foto Tim',
                'help_text' => 'Upload foto tim formal (JPG/PNG, maksimal 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'documents',
                'order_index' => 5
            ]
        ];

        foreach ($requirements as $requirement) {
            CompetitionRequirement::create(array_merge($requirement, [
                'competition_id' => $competition->id
            ]));
        }
    }

    private function createKDBICriteria($competition)
    {
        $criteria = [
            [
                'criteria_name' => 'Substansi dan Argumen',
                'description' => 'Kualitas substansi, kekuatan argumen, dan data pendukung',
                'weight_percentage' => 35,
                'sub_criteria' => json_encode([
                    'argument_quality' => 'Kualitas Argumen',
                    'supporting_data' => 'Data Pendukung',
                    'logical_flow' => 'Alur Logika'
                ]),
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Teknik Debat',
                'description' => 'Penguasaan teknik debat, bantahan, dan strategi',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'debate_technique' => 'Teknik Debat',
                    'rebuttal_skill' => 'Kemampuan Bantahan',
                    'strategy' => 'Strategi Debat'
                ]),
                'max_score' => 100,
                'order_index' => 2
            ],
            [
                'criteria_name' => 'Bahasa dan Komunikasi',
                'description' => 'Penggunaan bahasa Indonesia, artikulasi, dan komunikasi',
                'weight_percentage' => 25,
                'sub_criteria' => json_encode([
                    'language_usage' => 'Penggunaan Bahasa',
                    'articulation' => 'Artikulasi',
                    'communication' => 'Kemampuan Komunikasi'
                ]),
                'max_score' => 100,
                'order_index' => 3
            ],
            [
                'criteria_name' => 'Etika dan Sportivitas',
                'description' => 'Etika debat, sportivitas, dan sikap profesional',
                'weight_percentage' => 10,
                'sub_criteria' => json_encode([
                    'debate_ethics' => 'Etika Debat',
                    'sportsmanship' => 'Sportivitas',
                    'professionalism' => 'Profesionalisme'
                ]),
                'max_score' => 100,
                'order_index' => 4
            ]
        ];

        foreach ($criteria as $criterion) {
            CompetitionCriteria::create(array_merge($criterion, [
                'competition_id' => $competition->id
            ]));
        }
    }

    // SPC Requirements and Criteria
    private function createSPCRequirements($competition)
    {
        $requirements = [
            [
                'field_name' => 'paper_title',
                'field_type' => 'text',
                'field_label' => 'Judul Karya Tulis Ilmiah',
                'help_text' => 'Masukkan judul lengkap karya tulis ilmiah',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 200]),
                'field_group' => 'paper_info',
                'order_index' => 1
            ],
            [
                'field_name' => 'research_field',
                'field_type' => 'select',
                'field_label' => 'Bidang Penelitian',
                'help_text' => 'Pilih bidang penelitian karya tulis ilmiah',
                'is_required' => true,
                'field_options' => json_encode([
                    'technology' => 'Teknologi dan Inovasi',
                    'social' => 'Sosial dan Humaniora',
                    'environment' => 'Lingkungan dan Keberlanjutan',
                    'health' => 'Kesehatan dan Kedokteran',
                    'education' => 'Pendidikan dan Pembelajaran'
                ]),
                'field_group' => 'paper_info',
                'order_index' => 2
            ],
            [
                'field_name' => 'abstract',
                'field_type' => 'textarea',
                'field_label' => 'Abstrak',
                'help_text' => 'Tuliskan abstrak karya tulis ilmiah (maksimal 300 kata)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 2000]),
                'field_group' => 'paper_content',
                'order_index' => 3
            ],
            [
                'field_name' => 'keywords',
                'field_type' => 'text',
                'field_label' => 'Kata Kunci',
                'help_text' => 'Masukkan 3-5 kata kunci, pisahkan dengan koma',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 200]),
                'field_group' => 'paper_content',
                'order_index' => 4
            ],
            [
                'field_name' => 'research_methodology',
                'field_type' => 'textarea',
                'field_label' => 'Metodologi Penelitian',
                'help_text' => 'Jelaskan secara singkat metodologi penelitian yang digunakan',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 1000]),
                'field_group' => 'methodology',
                'order_index' => 5
            ],
            [
                'field_name' => 'full_paper',
                'field_type' => 'file',
                'field_label' => 'File Karya Tulis Lengkap',
                'help_text' => 'Upload file karya tulis lengkap (PDF, maksimal 10MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['pdf'], 'max_size' => 10240]),
                'field_group' => 'documents',
                'order_index' => 6
            ],
            [
                'field_name' => 'plagiarism_check',
                'field_type' => 'file',
                'field_label' => 'Hasil Cek Plagiarisme',
                'help_text' => 'Upload hasil cek plagiarisme (Turnitin/Grammarly, PDF)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['pdf'], 'max_size' => 5120]),
                'field_group' => 'documents',
                'order_index' => 7
            ]
        ];

        foreach ($requirements as $requirement) {
            CompetitionRequirement::create(array_merge($requirement, [
                'competition_id' => $competition->id
            ]));
        }
    }

    private function createSPCCriteria($competition)
    {
        $criteria = [
            [
                'criteria_name' => 'Originalitas dan Inovasi',
                'description' => 'Tingkat originalitas penelitian dan inovasi yang dihasilkan',
                'weight_percentage' => 25,
                'sub_criteria' => json_encode([
                    'originality' => 'Originalitas Penelitian',
                    'innovation' => 'Tingkat Inovasi',
                    'novelty' => 'Kebaruan Ide'
                ]),
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Metodologi Penelitian',
                'description' => 'Kualitas metodologi, validitas, dan reliabilitas penelitian',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'methodology_quality' => 'Kualitas Metodologi',
                    'validity' => 'Validitas Penelitian',
                    'reliability' => 'Reliabilitas Data'
                ]),
                'max_score' => 100,
                'order_index' => 2
            ],
            [
                'criteria_name' => 'Analisis dan Pembahasan',
                'description' => 'Kedalaman analisis, pembahasan hasil, dan interpretasi data',
                'weight_percentage' => 25,
                'sub_criteria' => json_encode([
                    'analysis_depth' => 'Kedalaman Analisis',
                    'discussion_quality' => 'Kualitas Pembahasan',
                    'data_interpretation' => 'Interpretasi Data'
                ]),
                'max_score' => 100,
                'order_index' => 3
            ],
            [
                'criteria_name' => 'Penulisan dan Presentasi',
                'description' => 'Kualitas penulisan, struktur paper, dan kemampuan presentasi',
                'weight_percentage' => 20,
                'sub_criteria' => json_encode([
                    'writing_quality' => 'Kualitas Penulisan',
                    'paper_structure' => 'Struktur Paper',
                    'presentation_skill' => 'Kemampuan Presentasi'
                ]),
                'max_score' => 100,
                'order_index' => 4
            ]
        ];

        foreach ($criteria as $criterion) {
            CompetitionCriteria::create(array_merge($criterion, [
                'competition_id' => $competition->id
            ]));
        }
    }

    // Infografis Requirements and Criteria
    private function createInfografisRequirements($competition)
    {
        $requirements = [
            [
                'field_name' => 'infographic_category',
                'field_type' => 'select',
                'field_label' => 'Kategori Infografis',
                'help_text' => 'Pilih kategori infografis yang akan dibuat',
                'is_required' => true,
                'field_options' => json_encode([
                    'educational' => 'Edukasi dan Pembelajaran',
                    'health' => 'Kesehatan dan Gaya Hidup',
                    'environment' => 'Lingkungan dan Alam',
                    'technology' => 'Teknologi dan Inovasi',
                    'culture' => 'Budaya dan Tradisi'
                ]),
                'field_group' => 'basic',
                'order_index' => 1
            ],
            [
                'field_name' => 'design_software',
                'field_type' => 'text',
                'field_label' => 'Software Desain',
                'help_text' => 'Sebutkan software desain yang akan digunakan (Adobe Illustrator, Canva, dll)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 200]),
                'field_group' => 'technical',
                'order_index' => 2
            ],
            [
                'field_name' => 'design_experience',
                'field_type' => 'textarea',
                'field_label' => 'Pengalaman Desain',
                'help_text' => 'Ceritakan pengalaman desain grafis/infografis Anda (kompetisi, proyek, dll)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 1000]),
                'field_group' => 'experience',
                'order_index' => 3
            ],
            [
                'field_name' => 'target_audience',
                'field_type' => 'text',
                'field_label' => 'Target Audience',
                'help_text' => 'Sebutkan target audience untuk infografis yang akan dibuat',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'concept',
                'order_index' => 4
            ],
            [
                'field_name' => 'design_concept',
                'field_type' => 'textarea',
                'field_label' => 'Konsep Desain',
                'help_text' => 'Jelaskan konsep dan ide desain infografis yang akan dibuat',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 500]),
                'field_group' => 'concept',
                'order_index' => 5
            ],
            [
                'field_name' => 'portfolio_link',
                'field_type' => 'url',
                'field_label' => 'Link Portfolio (Opsional)',
                'help_text' => 'Link ke portfolio desain sebelumnya (Behance, Dribbble, dll)',
                'is_required' => false,
                'validation_rules' => json_encode(['url' => true]),
                'field_group' => 'additional',
                'order_index' => 6
            ]
        ];

        foreach ($requirements as $requirement) {
            CompetitionRequirement::create(array_merge($requirement, [
                'competition_id' => $competition->id
            ]));
        }
    }

    private function createInfografisCriteria($competition)
    {
        $criteria = [
            [
                'criteria_name' => 'Desain dan Layout',
                'description' => 'Kualitas desain visual dan tata letak infografis',
                'weight_percentage' => 35,
                'sub_criteria' => json_encode([
                    'visual_design' => 'Desain Visual',
                    'layout' => 'Tata Letak',
                    'color_harmony' => 'Harmoni Warna'
                ]),
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Kreativitas dan Originalitas',
                'description' => 'Tingkat kreativitas dan keunikan karya infografis',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'creativity' => 'Kreativitas',
                    'originality' => 'Originalitas',
                    'innovation' => 'Inovasi Desain'
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
                    'information_accuracy' => 'Akurasi Informasi'
                ]),
                'max_score' => 100,
                'order_index' => 3
            ],
            [
                'criteria_name' => 'Komunikasi Visual',
                'description' => 'Efektivitas komunikasi visual dan informasi',
                'weight_percentage' => 10,
                'sub_criteria' => json_encode([
                    'visual_clarity' => 'Kejelasan Visual',
                    'information_flow' => 'Alur Informasi',
                    'readability' => 'Keterbacaan'
                ]),
                'max_score' => 100,
                'order_index' => 4
            ]
        ];

        foreach ($criteria as $criterion) {
            CompetitionCriteria::create(array_merge($criterion, [
                'competition_id' => $competition->id
            ]));
        }
    }
}
