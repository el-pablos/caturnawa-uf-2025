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
        // Create separate DCC competitions based on English PDF
        $this->createDCCInfographicsCompetition();
        $this->createDCCShortVideoCompetition();
        
        $this->createEDCCompetition();
        $this->createKDBICompetition();
        $this->createSPCCompetition();
    }

    // Create Infographics Competition (DCC Category)
    private function createDCCInfographicsCompetition()
    {
        try {
            $competition = Competition::create([
                'name' => 'Infographics Competition',
                'slug' => 'infographics-2025',
                'description' => 'The Infographics competition aims to encourage participants to think critically and creatively, while being able to present information in a concise, accurate, and easily understandable manner. Besides honing design skills and visual message delivery, participants are also invited to increase their awareness of global issues relevant to daily life.',
                'category' => 'event_dcc',
                'theme' => 'Conducting a Preventive Action for Deforestation Through AI-Assisted Technology Innovation in Acquiring a Resilience and Sustainable Ecosystem',
                'price' => 75000, // Phase II: IDR 75,000 per team (September 14–26, 2025)
                'early_bird_price' => 50000, // Early Bird: IDR 50,000 per team (August 25–31, 2025)
                'price_unas_student' => 65000, // Phase I: IDR 65,000 per team (September 1–13, 2025)
                'price_external_student' => 75000, // Phase II: IDR 75,000 per team
                'early_bird_deadline' => Carbon::create(2025, 8, 31, 23, 59, 59), // August 25–31, 2025
                'max_participants' => 15, // 15 teams for Infographics competition
                'max_team_members' => 3, // Teams consist of 3 members
                'min_team_members' => 3, // Teams consist of 3 members
                'registration_start' => Carbon::create(2025, 8, 25, 0, 0, 0), // Early Bird start
                'registration_end' => Carbon::create(2025, 9, 26, 23, 59, 59), // Phase II end
                'competition_start' => Carbon::create(2025, 9, 29, 8, 0, 0), // Webinar
                'competition_end' => Carbon::create(2025, 11, 10, 17, 0, 0), // Awarding
                'submission_start' => Carbon::create(2025, 10, 8, 0, 0, 0), // Work Submission/Launching
                'submission_end' => Carbon::create(2025, 10, 17, 23, 59, 59), // Work Submission end
                'judging_start' => Carbon::create(2025, 10, 18, 8, 0, 0), // Work Evaluation/Assessment
                'judging_end' => Carbon::create(2025, 10, 27, 17, 0, 0), // Final Round
                'announcement_date' => Carbon::create(2025, 11, 10, 14, 0, 0), // Awarding
                'is_active' => true,
                'is_team_competition' => true,
                'allow_individual' => false, // Teams must consist of 3 members
                'rules' => json_encode([
                    'Participants must be active high school students (SMA/MA/SMK or equivalent) in the JABODETABEK area',
                    'Participants are required to upload a statement letter of active student status issued by the school and a Student ID Card',
                    'Teams should consist of 3 members (the team may be composed entirely of female members, male members, or a mix)',
                    'Participants must follow the competition theme as determined by the committee',
                    'Submitted works must be the original creation of the participants, not plagiarized, and must not have been submitted or published in other competitions',
                    'Works must not contain elements of SARA (ethnic, religious, racial, and inter-group issues), violence, pornography, hate speech, foul language, or other content that violates the norms, ethics, and laws applicable in Indonesia',
                    'Participants are required to upload their works via specified social media platforms (YouTube, Instagram, or TikTok) including the official competition hashtag and tagging the official UNAS FEST account',
                    'The social media account used for uploading must be public (not private) throughout the competition period',
                    'Participants are free to use any graphic design software such as Freehand, Corel Draw, Adobe Photoshop, Canva, or similar applications; however, the use of AI-based software is prohibited',
                    'The infographic design quality must be full HD resolution to ensure optimal image sharpness and compliance with platform specifications',
                    'Infographic works must combine text, graphics, illustrations, and icons that support each other to deliver information clearly, systematically, and effectively',
                    'Recommended infographic design size is in a 4:5 (portrait) ratio to optimize display on social media',
                    'The judges\' decisions are final, binding, and cannot be contested'
                ]),
                'prizes' => json_encode([
                    'juara_1' => 'Champion Prize + Trophy + Certificate + Merchandise',
                    'juara_2' => 'Runner-up Prize + Trophy + Certificate + Merchandise', 
                    'juara_3' => '3rd Place Prize + Trophy + Certificate + Merchandise'
                ]),
                'contact_person_name' => 'Infographics Competition Team UNAS FEST 2025',
                'contact_person_whatsapp' => '+62 817-8901-2345',
                'guidelines' => 'The competition targets 15 teams consisting of active high school students from SMA, MAN, and SMK in the JABODETABEK area. The competition consists of three stages: Administrative Selection, Semifinals, and Finals—all conducted online, followed by an onsite awarding ceremony.',
                'submission_formats' => json_encode([
                    'image' => ['jpg', 'png'],
                    'document' => ['pdf']
                ])
            ]);

            $this->createDCCInfographicsRequirements($competition);
            $this->createDCCInfographicsCriteria($competition);
        } catch (\Exception $e) {
            \Log::error('Error creating DCC Infographics competition: ' . $e->getMessage());
            throw $e;
        }
    }

    // Create Short Video Competition (DCC Category)
    private function createDCCShortVideoCompetition()
    {
        try {
            $competition = Competition::create([
                'name' => 'Short Video Competition',
                'slug' => 'short-video-2025',
                'description' => 'The Short Video competition is a contest for brief videos typically lasting from 15 seconds up to 3 minutes, designed to convey messages in a concise, creative, and informative manner. The activity begins with a webinar aimed at providing participants with the skills, knowledge, and understanding needed to create effective Short Video works.',
                'category' => 'event_dcc',
                'theme' => 'Conducting a Preventive Action for Deforestation Through AI-Assisted Technology Innovation in Acquiring a Resilience and Sustainable Ecosystem',
                'price' => 75000, // Phase II: IDR 75,000 per team (September 14–26, 2025)
                'early_bird_price' => 50000, // Early Bird: IDR 50,000 per team (August 25–31, 2025)
                'price_unas_student' => 65000, // Phase I: IDR 65,000 per team (September 1–13, 2025)
                'price_external_student' => 75000, // Phase II: IDR 75,000 per team
                'early_bird_deadline' => Carbon::create(2025, 8, 31, 23, 59, 59), // August 25–31, 2025
                'max_participants' => 15, // 15 teams for Short Video competition
                'max_team_members' => 3, // Teams consist of 3 members
                'min_team_members' => 3, // Teams consist of 3 members
                'registration_start' => Carbon::create(2025, 8, 25, 0, 0, 0), // Early Bird start
                'registration_end' => Carbon::create(2025, 9, 26, 23, 59, 59), // Phase II end
                'competition_start' => Carbon::create(2025, 9, 29, 8, 0, 0), // Webinar
                'competition_end' => Carbon::create(2025, 11, 10, 17, 0, 0), // Awarding
                'submission_start' => Carbon::create(2025, 10, 8, 0, 0, 0), // Work Submission/Launching
                'submission_end' => Carbon::create(2025, 10, 17, 23, 59, 59), // Work Submission end
                'judging_start' => Carbon::create(2025, 10, 18, 8, 0, 0), // Work Evaluation/Assessment
                'judging_end' => Carbon::create(2025, 10, 27, 17, 0, 0), // Final Round
                'announcement_date' => Carbon::create(2025, 11, 10, 14, 0, 0), // Awarding
                'is_active' => true,
                'is_team_competition' => true,
                'allow_individual' => false, // Teams must consist of 3 members
                'rules' => json_encode([
                    'Participants must be active high school students (SMA/MA/SMK or equivalent) in the JABODETABEK area',
                    'Participants are required to upload a statement letter of active student status issued by the school and a Student ID Card',
                    'Teams should consist of 3 members (the team may be composed entirely of female members, male members, or a mix)',
                    'Video duration must be a maximum of 3 minutes and a minimum of 60 seconds',
                    'The video theme must correspond to the competition theme determined by the committee',
                    'Submitted videos must be original and not previously published or entered into any other competition',
                    'Use of content containing elements of SARA (ethnic, religious, racial, and social issues), pornography, violence, or plagiarism is prohibited',
                    'Each participant may submit only one (1) video',
                    'Accepted video formats: MP4 or MOV, with a minimum resolution of 720p',
                    'Language is free; if using regional or foreign languages, Indonesian subtitles are required',
                    'Videos must not violate copyright laws (music, footage, etc. must be licensed or authorized)',
                    'Participants must upload their works on specified social media platforms (YouTube, Instagram, or TikTok) including the official competition hashtag and tag the official UNAS FEST account',
                    'The social media account used to upload the work must be public (not private) throughout the competition period',
                    'Participants are free to use any graphic design software such as Freehand, Corel Draw, Adobe Photoshop, Canva, or similar applications; however, the use of AI-based software is prohibited',
                    'The judges\' decisions are final, binding, and cannot be contested'
                ]),
                'prizes' => json_encode([
                    'juara_1' => 'Champion Prize + Trophy + Certificate + Merchandise',
                    'juara_2' => 'Runner-up Prize + Trophy + Certificate + Merchandise',
                    'juara_3' => '3rd Place Prize + Trophy + Certificate + Merchandise'
                ]),
                'contact_person_name' => 'Short Video Competition Team UNAS FEST 2025',
                'contact_person_whatsapp' => '+62 818-9012-3456',
                'guidelines' => 'The competition targets 15 teams consisting of active high school students from SMA, MAN, and SMK in the JABODETABEK area. The competition consists of three stages: Administrative Selection, Semifinals, and Finals—all conducted online, followed by an onsite awarding ceremony.',
                'submission_formats' => json_encode([
                    'video' => ['mp4', 'mov']
                ])
            ]);

            $this->createDCCShortVideoRequirements($competition);
            $this->createDCCShortVideoCriteria($competition);
        } catch (\Exception $e) {
            \Log::error('Error creating DCC Short Video competition: ' . $e->getMessage());
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

    // DCC Requirements and Criteria sesuai DATA DIRI PESERTA DCC.pdf
    private function createDCCRequirements($competition)
    {
        $requirements = [
            // Data Anggota Tim (3 orang sesuai PDF)
            [
                'field_name' => 'team_name',
                'field_type' => 'text',
                'field_label' => 'Nama Tim',
                'help_text' => 'Masukkan nama tim untuk Digital Content Competition',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_info',
                'order_index' => 1
            ],
            [
                'field_name' => 'school_name',
                'field_type' => 'text',
                'field_label' => 'Nama Sekolah',
                'help_text' => 'Nama SMA/MA/SMK sederajat di JABODETABEK',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 150]),
                'field_group' => 'team_info',
                'order_index' => 2
            ],
            [
                'field_name' => 'competition_category',
                'field_type' => 'select',
                'field_label' => 'Cabang Lomba',
                'help_text' => 'Pilih salah satu cabang lomba DCC',
                'is_required' => true,
                'field_options' => json_encode([
                    'short_video' => 'Short Video (maksimal 3 menit)',
                    'infografis' => 'Infografis'
                ]),
                'field_group' => 'competition',
                'order_index' => 3
            ],
            
            // Required Documents sesuai PDF
            [
                'field_name' => 'pas_foto_3x4',
                'field_type' => 'file',
                'field_label' => 'Pas Foto 3x4',
                'help_text' => 'Upload pas foto 3x4 untuk setiap anggota tim (JPG/PNG, max 2MB per file)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'documents',
                'order_index' => 4
            ],
            [
                'field_name' => 'kartu_pelajar',
                'field_type' => 'file',
                'field_label' => 'Kartu Pelajar',
                'help_text' => 'Upload kartu pelajar untuk setiap anggota tim (JPG/PNG/PDF, max 2MB per file)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png', 'pdf'], 'max_size' => 2048]),
                'field_group' => 'documents',
                'order_index' => 5
            ],
            [
                'field_name' => 'surat_keterangan_aktif',
                'field_type' => 'file',
                'field_label' => 'Surat Keterangan Siswa/i Aktif',
                'help_text' => 'Upload surat keterangan siswa/i aktif yang dikeluarkan oleh pihak sekolah (PDF, max 5MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['pdf'], 'max_size' => 5120]),
                'field_group' => 'documents',
                'order_index' => 6
            ],
            
            // Social Media Requirements sesuai PDF
            [
                'field_name' => 'screenshot_ig_follow',
                'field_type' => 'file',
                'field_label' => 'Screenshot Follow Instagram UNAS FEST',
                'help_text' => 'Upload bukti screenshot follow Instagram UNAS FEST @Unasfest (JPG/PNG, max 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'social_media',
                'order_index' => 7
            ],
            [
                'field_name' => 'screenshot_tiktok_follow',
                'field_type' => 'file',
                'field_label' => 'Screenshot Follow TikTok UNAS FEST',
                'help_text' => 'Upload bukti screenshot follow TikTok UNAS FEST @Unasfest (JPG/PNG, max 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'social_media',
                'order_index' => 8
            ],
            [
                'field_name' => 'screenshot_youtube_follow',
                'field_type' => 'file',
                'field_label' => 'Screenshot Follow YouTube UNAS FEST',
                'help_text' => 'Upload bukti screenshot follow YouTube UNAS FEST @Unasfest (JPG/PNG, max 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'social_media',
                'order_index' => 9
            ],
            [
                'field_name' => 'screenshot_twibbon',
                'field_type' => 'file',
                'field_label' => 'Screenshot Upload Twibbon (Perwakilan)',
                'help_text' => 'Upload bukti screenshot mengupload twibbon (perwakilan tim) (JPG/PNG, max 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'social_media',
                'order_index' => 10
            ],
            
            // Team Members Details (3 orang)
            [
                'field_name' => 'member1_name',
                'field_type' => 'text',
                'field_label' => 'Nama Lengkap Anggota 1 (Ketua)',
                'help_text' => 'Nama lengkap anggota tim 1 sesuai kartu pelajar',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_members',
                'order_index' => 11
            ],
            [
                'field_name' => 'member1_class',
                'field_type' => 'text',
                'field_label' => 'Kelas Anggota 1',
                'help_text' => 'Kelas anggota tim 1 (contoh: XII IPA 1)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 50]),
                'field_group' => 'team_members',
                'order_index' => 12
            ],
            [
                'field_name' => 'member1_phone',
                'field_type' => 'text',
                'field_label' => 'No. WhatsApp Anggota 1',
                'help_text' => 'Nomor WhatsApp aktif anggota 1 (format: 08xxxxxxxxxx)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 15, 'pattern' => '^08[0-9]{8,12}$']),
                'field_group' => 'team_members',
                'order_index' => 13
            ],
            [
                'field_name' => 'member2_name',
                'field_type' => 'text',
                'field_label' => 'Nama Lengkap Anggota 2',
                'help_text' => 'Nama lengkap anggota tim 2 sesuai kartu pelajar',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_members',
                'order_index' => 14
            ],
            [
                'field_name' => 'member2_class',
                'field_type' => 'text',
                'field_label' => 'Kelas Anggota 2',
                'help_text' => 'Kelas anggota tim 2 (contoh: XII IPA 1)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 50]),
                'field_group' => 'team_members',
                'order_index' => 15
            ],
            [
                'field_name' => 'member2_phone',
                'field_type' => 'text',
                'field_label' => 'No. WhatsApp Anggota 2',
                'help_text' => 'Nomor WhatsApp aktif anggota 2 (format: 08xxxxxxxxxx)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 15, 'pattern' => '^08[0-9]{8,12}$']),
                'field_group' => 'team_members',
                'order_index' => 16
            ],
            [
                'field_name' => 'member3_name',
                'field_type' => 'text',
                'field_label' => 'Nama Lengkap Anggota 3',
                'help_text' => 'Nama lengkap anggota tim 3 sesuai kartu pelajar',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_members',
                'order_index' => 17
            ],
            [
                'field_name' => 'member3_class',
                'field_type' => 'text',
                'field_label' => 'Kelas Anggota 3',
                'help_text' => 'Kelas anggota tim 3 (contoh: XII IPA 1)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 50]),
                'field_group' => 'team_members',
                'order_index' => 18
            ],
            [
                'field_name' => 'member3_phone',
                'field_type' => 'text',
                'field_label' => 'No. WhatsApp Anggota 3',
                'help_text' => 'Nomor WhatsApp aktif anggota 3 (format: 08xxxxxxxxxx)',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 15, 'pattern' => '^08[0-9]{8,12}$']),
                'field_group' => 'team_members',
                'order_index' => 19
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

    // Infographics Competition Requirements (from English PDF)
    private function createDCCInfographicsRequirements($competition)
    {
        $requirements = [
            [
                'field_name' => 'team_name',
                'field_type' => 'text',
                'field_label' => 'Team Name',
                'help_text' => 'Enter team name for Infographics Competition',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_info',
                'order_index' => 1
            ],
            [
                'field_name' => 'school_name',
                'field_type' => 'text',
                'field_label' => 'School Name',
                'help_text' => 'Name of SMA/MA/SMK or equivalent in JABODETABEK area',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 150]),
                'field_group' => 'team_info',
                'order_index' => 2
            ],
            [
                'field_name' => 'student_certificate',
                'field_type' => 'file',
                'field_label' => 'Statement Letter of Active Student Status',
                'help_text' => 'Upload statement letter of active student status issued by the school (PDF, max 5MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['pdf'], 'max_size' => 5120]),
                'field_group' => 'documents',
                'order_index' => 3
            ],
            [
                'field_name' => 'student_id_card',
                'field_type' => 'file',
                'field_label' => 'Student ID Card',
                'help_text' => 'Upload Student ID Card for each team member (JPG/PNG/PDF, max 2MB per file)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png', 'pdf'], 'max_size' => 2048]),
                'field_group' => 'documents',
                'order_index' => 4
            ],
            [
                'field_name' => 'photo_3x4',
                'field_type' => 'file',
                'field_label' => '3 x 4 cm Photo',
                'help_text' => 'Upload 3 x 4 cm photo for each team member (JPG/PNG, max 2MB per file)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'documents',
                'order_index' => 5
            ],
            [
                'field_name' => 'instagram_follow_proof',
                'field_type' => 'file',
                'field_label' => 'Instagram Follow Screenshot',
                'help_text' => 'Screenshot proof of following @unasfest on Instagram (JPG/PNG, max 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'social_media',
                'order_index' => 6
            ],
            [
                'field_name' => 'youtube_follow_proof',
                'field_type' => 'file',
                'field_label' => 'YouTube Follow Screenshot',
                'help_text' => 'Screenshot proof of following @unasfest on YouTube (JPG/PNG, max 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'social_media',
                'order_index' => 7
            ],
            [
                'field_name' => 'tiktok_follow_proof',
                'field_type' => 'file',
                'field_label' => 'TikTok Follow Screenshot',
                'help_text' => 'Screenshot proof of following @unasfest on TikTok (JPG/PNG, max 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'social_media',
                'order_index' => 8
            ]
        ];

        foreach ($requirements as $requirement) {
            CompetitionRequirement::create(array_merge($requirement, [
                'competition_id' => $competition->id
            ]));
        }
    }

    // Infographics Competition Criteria (from English PDF scoring tabulator)
    private function createDCCInfographicsCriteria($competition)
    {
        // Implementation will use Score model's getDccInfografisCriteria method
        // No need to create separate criteria here as they are handled by the Score model
    }

    // Short Video Competition Requirements (from English PDF)
    private function createDCCShortVideoRequirements($competition)
    {
        $requirements = [
            [
                'field_name' => 'team_name',
                'field_type' => 'text',
                'field_label' => 'Team Name',
                'help_text' => 'Enter team name for Short Video Competition',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 100]),
                'field_group' => 'team_info',
                'order_index' => 1
            ],
            [
                'field_name' => 'school_name',
                'field_type' => 'text',
                'field_label' => 'School Name',
                'help_text' => 'Name of SMA/MA/SMK or equivalent in JABODETABEK area',
                'is_required' => true,
                'validation_rules' => json_encode(['max_length' => 150]),
                'field_group' => 'team_info',
                'order_index' => 2
            ],
            [
                'field_name' => 'student_certificate',
                'field_type' => 'file',
                'field_label' => 'Statement Letter of Active Student Status',
                'help_text' => 'Upload statement letter of active student status issued by the school (PDF, max 5MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['pdf'], 'max_size' => 5120]),
                'field_group' => 'documents',
                'order_index' => 3
            ],
            [
                'field_name' => 'student_id_card',
                'field_type' => 'file',
                'field_label' => 'Student ID Card',
                'help_text' => 'Upload Student ID Card for each team member (JPG/PNG/PDF, max 2MB per file)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png', 'pdf'], 'max_size' => 2048]),
                'field_group' => 'documents',
                'order_index' => 4
            ],
            [
                'field_name' => 'photo_3x4',
                'field_type' => 'file',
                'field_label' => '3 x 4 cm Photo',
                'help_text' => 'Upload 3 x 4 cm photo for each team member (JPG/PNG, max 2MB per file)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'documents',
                'order_index' => 5
            ],
            [
                'field_name' => 'instagram_follow_proof',
                'field_type' => 'file',
                'field_label' => 'Instagram Follow Screenshot',
                'help_text' => 'Screenshot proof of following @unasfest on Instagram (JPG/PNG, max 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'social_media',
                'order_index' => 6
            ],
            [
                'field_name' => 'youtube_follow_proof',
                'field_type' => 'file',
                'field_label' => 'YouTube Follow Screenshot',
                'help_text' => 'Screenshot proof of following @unasfest on YouTube (JPG/PNG, max 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'social_media',
                'order_index' => 7
            ],
            [
                'field_name' => 'tiktok_follow_proof',
                'field_type' => 'file',
                'field_label' => 'TikTok Follow Screenshot',
                'help_text' => 'Screenshot proof of following @unasfest on TikTok (JPG/PNG, max 2MB)',
                'is_required' => true,
                'validation_rules' => json_encode(['file_types' => ['jpg', 'jpeg', 'png'], 'max_size' => 2048]),
                'field_group' => 'social_media',
                'order_index' => 8
            ]
        ];

        foreach ($requirements as $requirement) {
            CompetitionRequirement::create(array_merge($requirement, [
                'competition_id' => $competition->id
            ]));
        }
    }

    // Short Video Competition Criteria (from English PDF scoring tabulator)
    private function createDCCShortVideoCriteria($competition)
    {
        // Implementation will use Score model's getDccShortVideoCriteria method
        // No need to create separate criteria here as they are handled by the Score model
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
