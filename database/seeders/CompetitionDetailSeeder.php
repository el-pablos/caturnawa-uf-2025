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
        \DB::statement('SET sql_mode = "";');
        CompetitionCriteria::truncate();
        CompetitionRequirement::truncate();
        Competition::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create competitions based on PDF
        $this->createDCCCompetition();
        $this->createEDCCompetition();
        $this->createKDBICompetition();
        $this->createSPCCompetition();
        $this->createInfografisCompetition();
    }

    private function createDCCCompetition()
    {
        try {
            $competition = Competition::create([
                'name' => 'Digital Content Competition (DCC)',
                'slug' => 'dcc-2025',
                'description' => 'Kompetisi kreativitas digital yang menantang peserta untuk menciptakan konten digital inovatif dengan tema "Digital Innovation for Sustainable Future". Peserta dapat membuat video, animasi, infografis digital, atau desain grafis yang menginspirasi dan memberikan dampak positif bagi masyarakat.',
                'category' => 'event_dcc',
                'price' => 75000,
                'early_bird_price' => 60000,
                'early_bird_deadline' => Carbon::create(2025, 7, 31, 23, 59, 59),
                'max_participants' => 150,
                'max_team_members' => 3,
                'min_team_members' => 1,
                'registration_start' => Carbon::create(2025, 7, 1, 0, 0, 0),
                'registration_end' => Carbon::create(2025, 8, 31, 23, 59, 59),
                'competition_start' => Carbon::create(2025, 9, 15, 8, 0, 0),
                'competition_end' => Carbon::create(2025, 9, 17, 17, 0, 0),
                'submission_start' => Carbon::create(2025, 9, 1, 0, 0, 0),
                'submission_end' => Carbon::create(2025, 9, 14, 23, 59, 59),
                'judging_start' => Carbon::create(2025, 9, 18, 8, 0, 0),
                'judging_end' => Carbon::create(2025, 9, 25, 17, 0, 0),
                'announcement_date' => Carbon::create(2025, 9, 30, 14, 0, 0),
                'is_active' => true,
                'is_team_competition' => true,
                'allow_individual' => true,
                'rules' => json_encode([
                    'Peserta wajib mahasiswa aktif S1/D3/D4 dari universitas/institusi pendidikan tinggi di Indonesia',
                    'Karya yang disubmit harus original dan belum pernah dipublikasikan atau memenangkan kompetisi lain',
                    'Tema wajib: "Digital Innovation for Sustainable Future"',
                    'Format konten: Video (max 5 menit), Animasi (max 3 menit), Infografis Digital, atau Desain Grafis',
                    'File submission dalam format MP4 untuk video/animasi, PNG/JPG untuk infografis/desain (min 300 DPI)',
                    'Mematuhi timeline yang telah ditetapkan',
                    'Tidak mengandung unsur SARA, pornografi, atau melanggar hukum',
                    'Keputusan juri bersifat final dan tidak dapat diganggu gugat'
                ]),
                'prizes' => json_encode([
                    'juara_1' => 'Rp 5.000.000 + Sertifikat + Trophy + Merchandise',
                    'juara_2' => 'Rp 3.000.000 + Sertifikat + Trophy + Merchandise',
                    'juara_3' => 'Rp 2.000.000 + Sertifikat + Trophy + Merchandise',
                    'favorite_choice' => 'Rp 1.000.000 + Sertifikat + Merchandise',
                    'best_innovation' => 'Rp 1.000.000 + Sertifikat + Merchandise'
                ]),
                'contact_person_name' => 'Tim DCC Caturnawa UNAS FEST 2025',
                'contact_person_whatsapp' => '+62 812-3456-7890',
                'guidelines' => 'Peserta wajib mengikuti semua tahapan kompetisi dari registrasi hingga pengumuman pemenang. Karya yang tidak sesuai tema atau melanggar aturan akan didiskualifikasi.',
                'submission_formats' => json_encode([
                    'video' => ['mp4', 'mov', 'avi'],
                    'image' => ['png', 'jpg', 'jpeg'],
                    'document' => ['pdf']
                ])
            ]);

            $this->createDCCRequirements($competition);
            $this->createDCCCriteria($competition);
        } catch (\Exception $e) {
            \Log::error('Error creating DCC competition: ' . $e->getMessage());
            throw $e;
        }
    }

    private function createEDCCompetition()
    {
        $competition = Competition::create([
            'name' => 'English Debate Competition (EDC)',
            'slug' => 'edc-2025',
            'description' => 'Kompetisi debat bahasa Inggris tingkat nasional yang menguji kemampuan argumentasi, public speaking, dan critical thinking peserta dalam bahasa Inggris. Format debat menggunakan Asian Parliamentary Debate System dengan mosi-mosi aktual dan relevan.',
            'category' => 'event_debate',
            'price' => 200000,
            'early_bird_price' => 150000,
            'early_bird_deadline' => Carbon::create(2025, 8, 15, 23, 59, 59),
            'max_participants' => 64,
            'max_team_members' => 3,
            'min_team_members' => 3,
            'registration_start' => Carbon::create(2025, 7, 15, 0, 0, 0),
            'registration_end' => Carbon::create(2025, 8, 31, 23, 59, 59),
            'competition_start' => Carbon::create(2025, 9, 20, 8, 0, 0),
            'competition_end' => Carbon::create(2025, 9, 22, 17, 0, 0),
            'submission_start' => null,
            'submission_end' => null,
            'judging_start' => Carbon::create(2025, 9, 20, 8, 0, 0),
            'judging_end' => Carbon::create(2025, 9, 22, 17, 0, 0),
            'announcement_date' => Carbon::create(2025, 9, 22, 19, 0, 0),
            'is_active' => true,
            'is_team_competition' => true,
            'allow_individual' => false,
            'rules' => json_encode([
                'Tim terdiri dari 3 orang mahasiswa aktif dari universitas/institusi yang sama',
                'Menggunakan format Asian Parliamentary Debate System',
                'Bahasa pengantar wajib bahasa Inggris',
                'Setiap speaker mendapat waktu 7 menit untuk menyampaikan argumen',
                'Point of Information (POI) diperbolehkan setelah menit pertama hingga menit keenam',
                'Mengikuti code of conduct debat yang telah ditetapkan',
                'Dress code: formal/semi-formal',
                'Keputusan juri bersifat final dan tidak dapat diganggu gugat'
            ]),
            'prizes' => json_encode([
                'champion' => 'Rp 7.500.000 + Trophy + Sertifikat + Merchandise',
                'runner_up' => 'Rp 5.000.000 + Trophy + Sertifikat + Merchandise',
                'semifinalist_1' => 'Rp 2.500.000 + Sertifikat + Merchandise',
                'semifinalist_2' => 'Rp 2.500.000 + Sertifikat + Merchandise',
                'best_speaker' => 'Rp 1.500.000 + Trophy + Sertifikat + Merchandise',
                'best_interjection' => 'Rp 1.000.000 + Sertifikat + Merchandise'
            ]),
            'contact_person_name' => 'Tim EDC Caturnawa UNAS FEST 2025',
            'contact_person_whatsapp' => '+62 813-4567-8901',
            'guidelines' => 'Peserta wajib hadir tepat waktu sesuai jadwal yang telah ditentukan. Keterlambatan dapat mengakibatkan diskualifikasi.',
            'submission_formats' => json_encode([
                'document' => ['pdf']
            ])
        ]);

        $this->createEDCRequirements($competition);
        $this->createEDCCriteria($competition);
    }

    private function createKDBICompetition()
    {
        $competition = Competition::create([
            'name' => 'Kompetisi Debat Bahasa Indonesia (KDBI)',
            'slug' => 'kdbi-2025',
            'description' => 'Kompetisi debat bahasa Indonesia yang bertujuan mengasah kemampuan berargumentasi, berpikir kritis, dan public speaking peserta dalam bahasa Indonesia yang baik dan benar. Format debat menggunakan sistem 3 vs 3 dengan tema-tema aktual dan strategis.',
            'category' => 'event_debate',
            'price' => 150000,
            'early_bird_price' => 100000,
            'early_bird_deadline' => Carbon::create(2025, 8, 20, 23, 59, 59),
            'max_participants' => 48,
            'max_team_members' => 3,
            'min_team_members' => 3,
            'registration_start' => Carbon::create(2025, 8, 1, 0, 0, 0),
            'registration_end' => Carbon::create(2025, 9, 15, 23, 59, 59),
            'competition_start' => Carbon::create(2025, 10, 5, 8, 0, 0),
            'competition_end' => Carbon::create(2025, 10, 7, 17, 0, 0),
            'submission_start' => null,
            'submission_end' => null,
            'judging_start' => Carbon::create(2025, 10, 5, 8, 0, 0),
            'judging_end' => Carbon::create(2025, 10, 7, 17, 0, 0),
            'announcement_date' => Carbon::create(2025, 10, 7, 19, 0, 0),
            'is_active' => true,
            'is_team_competition' => true,
            'allow_individual' => false,
            'rules' => json_encode([
                'Tim terdiri dari 3 orang mahasiswa aktif dari universitas/institusi yang sama',
                'Menggunakan bahasa Indonesia yang baik dan benar sesuai EYD',
                'Format debat 3 vs 3 dengan waktu 6 menit per speaker',
                'Mengikuti etika debat dan sportivitas yang tinggi',
                'Dress code: formal/semi-formal dengan identitas universitas',
                'Mematuhi time management yang telah ditetapkan',
                'Tidak diperkenankan menggunakan bahasa daerah atau asing',
                'Keputusan juri bersifat final dan tidak dapat diganggu gugat'
            ]),
            'prizes' => json_encode([
                'juara_1' => 'Rp 4.000.000 + Trophy + Sertifikat + Merchandise',
                'juara_2' => 'Rp 2.500.000 + Trophy + Sertifikat + Merchandise',
                'juara_3' => 'Rp 1.500.000 + Trophy + Sertifikat + Merchandise',
                'best_speaker' => 'Rp 1.000.000 + Trophy + Sertifikat + Merchandise',
                'best_team_work' => 'Rp 750.000 + Sertifikat + Merchandise'
            ]),
            'contact_person_name' => 'Tim KDBI Caturnawa UNAS FEST 2025',
            'contact_person_whatsapp' => '+62 814-5678-9012',
            'guidelines' => 'Peserta wajib menggunakan bahasa Indonesia formal dan menghindari penggunaan bahasa gaul atau tidak baku.',
            'submission_formats' => json_encode([
                'document' => ['pdf']
            ])
        ]);

        $this->createKDBIRequirements($competition);
        $this->createKDBICriteria($competition);
    }

    private function createSPCCompetition()
    {
        $competition = Competition::create([
            'name' => 'Scientific Paper Competition (SPC)',
            'slug' => 'spc-2025',
            'description' => 'Kompetisi karya tulis ilmiah yang menantang mahasiswa untuk menghasilkan penelitian berkualitas tinggi dengan tema "Innovation and Technology for Sustainable Development". Peserta dapat memilih sub-tema dalam bidang teknologi, sosial, lingkungan, atau kesehatan.',
            'category' => 'event_scientific_paper',
            'price' => 100000,
            'early_bird_price' => 75000,
            'early_bird_deadline' => Carbon::create(2025, 8, 31, 23, 59, 59),
            'max_participants' => 100,
            'max_team_members' => 3,
            'min_team_members' => 1,
            'registration_start' => Carbon::create(2025, 7, 1, 0, 0, 0),
            'registration_end' => Carbon::create(2025, 9, 30, 23, 59, 59),
            'competition_start' => Carbon::create(2025, 10, 15, 8, 0, 0),
            'competition_end' => Carbon::create(2025, 10, 17, 17, 0, 0),
            'submission_start' => Carbon::create(2025, 9, 1, 0, 0, 0),
            'submission_end' => Carbon::create(2025, 10, 10, 23, 59, 59),
            'judging_start' => Carbon::create(2025, 10, 11, 8, 0, 0),
            'judging_end' => Carbon::create(2025, 10, 14, 17, 0, 0),
            'announcement_date' => Carbon::create(2025, 10, 17, 16, 0, 0),
            'is_active' => true,
            'is_team_competition' => true,
            'allow_individual' => true,
            'rules' => json_encode([
                'Karya tulis harus original dan belum pernah dipublikasikan atau memenangkan kompetisi lain',
                'Mengikuti format penulisan ilmiah yang telah ditentukan (template disediakan)',
                'Maksimal 15 halaman (tidak termasuk cover, daftar pustaka, dan lampiran)',
                'Menggunakan referensi minimal 15 sumber (jurnal ilmiah, buku, prosiding)',
                'Referensi terbaru minimal 70% dari 10 tahun terakhir',
                'Tema wajib: "Innovation and Technology for Sustainable Development"',
                'Menggunakan bahasa Indonesia atau bahasa Inggris yang baik dan benar',
                'Similarity index maksimal 20% (hasil Turnitin/Grammarly wajib dilampirkan)',
                'Keputusan juri bersifat final dan tidak dapat diganggu gugat'
            ]),
            'prizes' => json_encode([
                'juara_1' => 'Rp 6.000.000 + Trophy + Sertifikat + Publikasi Jurnal + Merchandise',
                'juara_2' => 'Rp 4.000.000 + Trophy + Sertifikat + Merchandise',
                'juara_3' => 'Rp 2.500.000 + Trophy + Sertifikat + Merchandise',
                'best_innovation' => 'Rp 1.500.000 + Sertifikat + Merchandise',
                'best_methodology' => 'Rp 1.500.000 + Sertifikat + Merchandise'
            ]),
            'contact_person_name' => 'Tim SPC Caturnawa UNAS FEST 2025',
            'contact_person_whatsapp' => '+62 815-6789-0123',
            'guidelines' => 'Paper harus mengikuti template yang disediakan dan memenuhi standar penulisan ilmiah internasional.',
            'submission_formats' => json_encode([
                'document' => ['pdf', 'docx']
            ])
        ]);

        $this->createSPCRequirements($competition);
        $this->createSPCCriteria($competition);
    }

    private function createInfografisCompetition()
    {
        $competition = Competition::create([
            'name' => 'Kompetisi Infografis',
            'slug' => 'infografis-2025',
            'description' => 'Kompetisi desain infografis yang menantang peserta untuk menyajikan informasi kompleks dalam bentuk visual yang menarik, mudah dipahami, dan informatif. Tema: "Data Visualization for Better Understanding" dengan fokus pada isu-isu sosial, lingkungan, teknologi, atau kesehatan.',
            'category' => 'event_dcc',
            'price' => 75000,
            'early_bird_price' => 60000,
            'early_bird_deadline' => Carbon::create(2025, 8, 15, 23, 59, 59),
            'max_participants' => 100,
            'max_team_members' => 2,
            'min_team_members' => 1,
            'registration_start' => Carbon::create(2025, 7, 15, 0, 0, 0),
            'registration_end' => Carbon::create(2025, 9, 15, 23, 59, 59),
            'competition_start' => Carbon::create(2025, 10, 1, 8, 0, 0),
            'competition_end' => Carbon::create(2025, 10, 3, 17, 0, 0),
            'submission_start' => Carbon::create(2025, 9, 16, 0, 0, 0),
            'submission_end' => Carbon::create(2025, 9, 30, 23, 59, 59),
            'judging_start' => Carbon::create(2025, 10, 4, 8, 0, 0),
            'judging_end' => Carbon::create(2025, 10, 10, 17, 0, 0),
            'announcement_date' => Carbon::create(2025, 10, 12, 14, 0, 0),
            'is_active' => true,
            'is_team_competition' => true,
            'allow_individual' => true,
            'rules' => json_encode([
                'Desain harus original dan belum pernah dipublikasikan atau memenangkan kompetisi lain',
                'Format digital: PNG/JPG dengan resolusi minimal 300 DPI',
                'Ukuran maksimal: A3 (297 x 420 mm) atau setara dalam pixel',
                'Tema wajib: "Data Visualization for Better Understanding"',
                'Menggunakan data yang akurat dan dapat dipertanggungjawabkan',
                'Mencantumkan sumber data yang digunakan',
                'Tidak mengandung unsur SARA, pornografi, atau melanggar hukum',
                'Menggunakan bahasa Indonesia atau bahasa Inggris',
                'File submission maksimal 10 MB',
                'Keputusan juri bersifat final dan tidak dapat diganggu gugat'
            ]),
            'prizes' => json_encode([
                'juara_1' => 'Rp 3.000.000 + Trophy + Sertifikat + Merchandise',
                'juara_2' => 'Rp 2.000.000 + Trophy + Sertifikat + Merchandise',
                'juara_3' => 'Rp 1.000.000 + Trophy + Sertifikat + Merchandise',
                'people_choice' => 'Rp 500.000 + Sertifikat + Merchandise',
                'best_data_visualization' => 'Rp 500.000 + Sertifikat + Merchandise'
            ]),
            'contact_person_name' => 'Tim Infografis Caturnawa UNAS FEST 2025',
            'contact_person_whatsapp' => '+62 816-7890-1234',
            'guidelines' => 'Infografis harus mudah dipahami, informatif, dan memiliki nilai edukasi yang tinggi untuk masyarakat umum.',
            'submission_formats' => json_encode([
                'image' => ['png', 'jpg', 'jpeg'],
                'document' => ['pdf']
            ])
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
                'help_text' => 'Masukkan nama lengkap ketua tim sesuai KTM/KTP',
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
                'help_text' => 'Nama universitas atau institusi pendidikan ketua tim',
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
                'field_name' => 'team_leader_phone',
                'field_type' => 'text',
                'field_label' => 'Nomor WhatsApp Ketua Tim',
                'help_text' => 'Nomor WhatsApp aktif ketua tim (format: 08xxxxxxxxxx)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 15, 'pattern' => '^08[0-9]{8,12}$']),
                'field_group' => 'team_leader',
                'order_index' => 5
            ],
            [
                'field_name' => 'content_category',
                'field_type' => 'select',
                'field_label' => 'Kategori Konten Digital',
                'help_text' => 'Pilih kategori konten digital yang akan dibuat',
                'is_required' => true,
                'field_options' => json_encode([
                    'video' => 'Video/Film Pendek (max 5 menit)',
                    'animation' => 'Animasi (max 3 menit)',
                    'infographic' => 'Infografis Digital',
                    'graphic_design' => 'Desain Grafis'
                ]),
                'field_group' => 'competition',
                'order_index' => 6
            ],
            [
                'field_name' => 'content_concept',
                'field_type' => 'textarea',
                'field_label' => 'Konsep Konten',
                'help_text' => 'Jelaskan konsep dan ide konten digital yang akan dibuat (maksimal 500 kata)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 3000]),
                'field_group' => 'competition',
                'order_index' => 7
            ],
            [
                'field_name' => 'software_used',
                'field_type' => 'text',
                'field_label' => 'Software yang Digunakan',
                'help_text' => 'Sebutkan software/aplikasi yang akan digunakan untuk membuat konten',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 200]),
                'field_group' => 'technical',
                'order_index' => 8
            ],
            [
                'field_name' => 'portfolio_link',
                'field_type' => 'url',
                'field_label' => 'Link Portfolio (Opsional)',
                'help_text' => 'Link ke portfolio karya digital sebelumnya (Google Drive, Behance, Instagram, dll)',
                'is_required' => false,
                'validation_rules' => json_encode(['url' => true]),
                'field_group' => 'additional',
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
                'description' => 'Penilaian terhadap tingkat kreativitas, originalitas ide, dan inovasi dalam eksekusi konten digital',
                'weight_percentage' => 25,
                'sub_criteria' => json_encode([
                    'originality' => 'Originalitas Ide dan Konsep',
                    'innovation' => 'Inovasi dalam Eksekusi',
                    'uniqueness' => 'Keunikan Pendekatan'
                ]),
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Kualitas Teknis dan Produksi',
                'description' => 'Penilaian terhadap kualitas teknis produksi, visual/audio, dan penggunaan tools/software',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'visual_quality' => 'Kualitas Visual/Audio',
                    'production_technique' => 'Teknik Produksi',
                    'tool_mastery' => 'Penguasaan Tools/Software'
                ]),
                'max_score' => 100,
                'order_index' => 2
            ],
            [
                'criteria_name' => 'Relevansi Tema dan Pesan',
                'description' => 'Kesesuaian dengan tema kompetisi, target audience, dan efektivitas penyampaian pesan',
                'weight_percentage' => 25,
                'sub_criteria' => json_encode([
                    'theme_relevance' => 'Kesesuaian dengan Tema',
                    'target_audience' => 'Ketepatan Target Audience',
                    'message_clarity' => 'Kejelasan Penyampaian Pesan'
                ]),
                'max_score' => 100,
                'order_index' => 3
            ],
            [
                'criteria_name' => 'Dampak dan Engagement',
                'description' => 'Kemampuan konten untuk memberikan dampak positif dan engagement dengan audience',
                'weight_percentage' => 20,
                'sub_criteria' => json_encode([
                    'social_impact' => 'Dampak Sosial/Edukasi',
                    'engagement_potential' => 'Potensi Engagement',
                    'memorability' => 'Daya Ingat Konten'
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
                'help_text' => 'Nama tim untuk kompetisi debat bahasa Inggris',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 50]),
                'field_group' => 'team_info',
                'order_index' => 1
            ],
            [
                'field_name' => 'university_name',
                'field_type' => 'text',
                'field_label' => 'Nama Universitas/Institusi',
                'help_text' => 'Nama universitas atau institusi yang diwakili',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_info',
                'order_index' => 2
            ],
            [
                'field_name' => 'debate_experience',
                'field_type' => 'textarea',
                'field_label' => 'Pengalaman Debat Tim',
                'help_text' => 'Ceritakan pengalaman debat bahasa Inggris anggota tim (kompetisi, prestasi, pelatihan, dll)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 1500]),
                'field_group' => 'experience',
                'order_index' => 3
            ],
            [
                'field_name' => 'english_proficiency',
                'field_type' => 'select',
                'field_label' => 'Tingkat Kemampuan Bahasa Inggris Tim',
                'help_text' => 'Pilih tingkat kemampuan bahasa Inggris rata-rata tim',
                'is_required' => true,
                'field_options' => json_encode([
                    'intermediate' => 'Intermediate (B1)',
                    'upper_intermediate' => 'Upper Intermediate (B2)',
                    'advanced' => 'Advanced (C1)',
                    'proficient' => 'Proficient (C2)'
                ]),
                'field_group' => 'skills',
                'order_index' => 4
            ],
            [
                'field_name' => 'team_photo',
                'field_type' => 'file',
                'field_label' => 'Foto Tim',
                'help_text' => 'Upload foto tim formal dengan dress code yang sesuai (JPG/PNG, maksimal 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'documents',
                'order_index' => 5
            ],
            [
                'field_name' => 'commitment_letter',
                'field_type' => 'file',
                'field_label' => 'Surat Komitmen',
                'help_text' => 'Upload surat komitmen mengikuti kompetisi hingga selesai dan mematuhi semua aturan (PDF, maksimal 2MB)',
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
                'description' => 'Kualitas konten, kekuatan argumen, dan relevansi dengan mosi debat',
                'weight_percentage' => 40,
                'sub_criteria' => json_encode([
                    'argument_strength' => 'Kekuatan dan Logika Argumen',
                    'evidence_quality' => 'Kualitas Bukti dan Data',
                    'relevance' => 'Relevansi dengan Mosi'
                ]),
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Style and Delivery',
                'description' => 'Gaya penyampaian, bahasa tubuh, dan kemampuan public speaking dalam bahasa Inggris',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'speaking_style' => 'Gaya dan Teknik Berbicara',
                    'body_language' => 'Bahasa Tubuh dan Gestur',
                    'voice_projection' => 'Proyeksi Suara dan Intonasi'
                ]),
                'max_score' => 100,
                'order_index' => 2
            ],
            [
                'criteria_name' => 'Strategy and Structure',
                'description' => 'Strategi debat, struktur argumen, dan manajemen waktu',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'debate_strategy' => 'Strategi dan Taktik Debat',
                    'argument_structure' => 'Struktur dan Organisasi Argumen',
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
                'field_label' => 'Nama Universitas/Institusi',
                'help_text' => 'Nama universitas atau institusi yang diwakili',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_info',
                'order_index' => 2
            ],
            [
                'field_name' => 'debate_experience_id',
                'field_type' => 'textarea',
                'field_label' => 'Pengalaman Debat Bahasa Indonesia',
                'help_text' => 'Ceritakan pengalaman debat bahasa Indonesia anggota tim (kompetisi, prestasi, pelatihan, dll)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 1500]),
                'field_group' => 'experience',
                'order_index' => 3
            ],
            [
                'field_name' => 'motivation_letter',
                'field_type' => 'textarea',
                'field_label' => 'Surat Motivasi',
                'help_text' => 'Tuliskan motivasi tim mengikuti KDBI Caturnawa UNAS FEST 2025 dan kontribusi yang diharapkan',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 1000]),
                'field_group' => 'motivation',
                'order_index' => 4
            ],
            [
                'field_name' => 'team_photo',
                'field_type' => 'file',
                'field_label' => 'Foto Tim',
                'help_text' => 'Upload foto tim formal dengan identitas universitas (JPG/PNG, maksimal 2MB)',
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
                'description' => 'Kualitas substansi, kekuatan argumen, dan data pendukung dalam bahasa Indonesia',
                'weight_percentage' => 35,
                'sub_criteria' => json_encode([
                    'argument_quality' => 'Kualitas dan Logika Argumen',
                    'supporting_data' => 'Data dan Fakta Pendukung',
                    'logical_flow' => 'Alur Logika Berpikir'
                ]),
                'max_score' => 100,
                'order_index' => 1
            ],
            [
                'criteria_name' => 'Teknik Debat',
                'description' => 'Penguasaan teknik debat, kemampuan bantahan, dan strategi debat',
                'weight_percentage' => 30,
                'sub_criteria' => json_encode([
                    'debate_technique' => 'Teknik dan Metode Debat',
                    'rebuttal_skill' => 'Kemampuan Bantahan',
                    'strategy' => 'Strategi dan Taktik'
                ]),
                'max_score' => 100,
                'order_index' => 2
            ],
            [
                'criteria_name' => 'Bahasa dan Komunikasi',
                'description' => 'Penggunaan bahasa Indonesia yang baik dan benar, artikulasi, dan kemampuan komunikasi',
                'weight_percentage' => 25,
                'sub_criteria' => json_encode([
                    'language_usage' => 'Penggunaan Bahasa Indonesia',
                    'articulation' => 'Artikulasi dan Diksi',
                    'communication' => 'Kemampuan Komunikasi'
                ]),
                'max_score' => 100,
                'order_index' => 3
            ],
            [
                'criteria_name' => 'Etika dan Sportivitas',
                'description' => 'Etika debat, sportivitas, dan sikap profesional selama kompetisi',
                'weight_percentage' => 10,
                'sub_criteria' => json_encode([
                    'debate_ethics' => 'Etika dalam Berdebat',
                    'sportsmanship' => 'Sportivitas',
                    'professionalism' => 'Sikap Profesional'
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

    // Placeholder methods for SPC and Infografis (to be implemented)
    private function createSPCRequirements($competition) { /* TODO */ }
    private function createSPCCriteria($competition) { /* TODO */ }
    private function createInfografisRequirements($competition) { /* TODO */ }
    private function createInfografisCriteria($competition) { /* TODO */ }
}
