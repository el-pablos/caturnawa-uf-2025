<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\CompetitionRequirement;
use App\Models\CompetitionCriteria;
use Carbon\Carbon;

class UpdatedCompetitionSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CompetitionCriteria::truncate();
        CompetitionRequirement::truncate();
        Competition::where('slug', 'like', '%-2025')->delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create competitions based on PDF requirements
        $this->createSPCCompetition();
        $this->createEDCCompetition();  
        $this->createKDBICompetition();
        $this->createDCCInfographicsCompetition();
        $this->createDCCShortVideoCompetition();
    }

    // SPC Competition based on SPC.122 PDF
    private function createSPCCompetition()
    {
        $competition = Competition::create([
            'name' => 'Scientific Paper Competition (SPC)',
            'slug' => 'spc-2025',
            'description' => 'SPC (Scientific Paper Competition) merupakan kompetisi karya tulis ilmiah yang ditujukan untuk mahasiswa aktif S1/D4 se-Indonesia. Kompetisi ini bertujuan untuk mengembangkan kemampuan penelitian dan penulisan ilmiah mahasiswa dengan tema yang relevan dan up-to-date.',
            'category' => 'event_scientific_paper',
            'theme' => 'Innovation and Technology for Sustainable Development',
            'price' => 150000, // Regular price per team
            'early_bird_price' => 100000, // Early bird price per team 
            'price_unas_student' => 100000, // UNAS student price
            'price_external_student' => 150000, // External student price
            'early_bird_deadline' => Carbon::create(2025, 8, 31, 23, 59, 59),
            'max_participants' => 150, // 150 teams as per PDF
            'max_team_members' => 3, // 1-3 members per team
            'min_team_members' => 1,
            'registration_start' => Carbon::create(2025, 8, 25, 0, 0, 0),
            'registration_end' => Carbon::create(2025, 9, 30, 23, 59, 59),
            'competition_start' => Carbon::create(2025, 10, 15, 8, 0, 0),
            'competition_end' => Carbon::create(2025, 11, 15, 17, 0, 0),
            'submission_start' => Carbon::create(2025, 10, 1, 0, 0, 0),
            'submission_end' => Carbon::create(2025, 10, 30, 23, 59, 59),
            'judging_start' => Carbon::create(2025, 11, 1, 8, 0, 0),
            'judging_end' => Carbon::create(2025, 11, 14, 17, 0, 0),
            'announcement_date' => Carbon::create(2025, 11, 15, 16, 0, 0),
            'is_active' => true,
            'is_team_competition' => true,
            'allow_individual' => true,
            'rules' => json_encode([
                'Peserta merupakan mahasiswa aktif program sarjana (S1) atau diploma IV (D4) yang terdaftar di perguruan tinggi negeri atau swasta di Indonesia',
                'Tim terdiri dari 1-3 mahasiswa dari program studi yang sama atau berbeda',
                'Setiap mahasiswa hanya boleh terdaftar dalam satu tim',
                'Karya tulis harus asli dan belum pernah dipublikasikan atau memenangkan kompetisi sejenis',
                'Tema wajib: "Innovation and Technology for Sustainable Development"',
                'Karya tulis ditulis dalam bahasa Indonesia yang baik dan benar',
                'Format penulisan mengikuti template yang telah disediakan',
                'Jumlah halaman maksimal 20 halaman (termasuk cover, tidak termasuk daftar pustaka)',
                'Menggunakan referensi minimal 20 sumber yang relevan dan terkini (maksimal 10 tahun terakhir)',
                'Plagiarisme maksimal 20% (wajib menyertakan hasil cek plagiasi)',
                'Keputusan juri bersifat final dan tidak dapat diganggu gugat'
            ]),
            'prizes' => json_encode([
                'juara_1' => 'Rp 8.000.000 + Trophy + Sertifikat + Publikasi Jurnal',
                'juara_2' => 'Rp 5.000.000 + Trophy + Sertifikat',
                'juara_3' => 'Rp 3.000.000 + Trophy + Sertifikat',
                'best_presentation' => 'Rp 1.000.000 + Sertifikat',
                'best_innovation' => 'Rp 1.000.000 + Sertifikat'
            ]),
            'contact_person_name' => 'Tim SPC UNAS FEST 2025',
            'contact_person_whatsapp' => '+62 882-1944-5100',
            'guidelines' => 'Kompetisi karya tulis ilmiah untuk mahasiswa S1/D4 se-Indonesia dengan tema Innovation and Technology for Sustainable Development.',
            'submission_formats' => json_encode([
                'document' => ['pdf', 'docx']
            ])
        ]);
        $this->createSPCRequirements($competition);
        $this->createSPCCriteria($competition);
    }

    // EDC Competition based on REVISION 5 PDF
    private function createEDCCompetition()
    {
        $competition = Competition::create([
            'name' => 'English Debate Competition (EDC)',
            'slug' => 'edc-2025',
            'description' => 'English Debate Competition (EDC) is a prestigious academic debating competition designed to enhance participants\' critical thinking, public speaking, and argumentative skills in English. This competition follows international debate formats and standards.',
            'category' => 'event_debate',
            'theme' => 'Building Bridges Through Dialogue',
            'price' => 200000, // Standard registration fee
            'early_bird_price' => 150000, // Early bird discount
            'price_unas_student' => 150000, // UNAS student price
            'price_external_student' => 200000, // External student price
            'early_bird_deadline' => Carbon::create(2025, 8, 31, 23, 59, 59),
            'max_participants' => 32, // 32 individual participants (not teams)
            'max_team_members' => 1, // Individual competition
            'min_team_members' => 1,
            'registration_start' => Carbon::create(2025, 8, 25, 0, 0, 0),
            'registration_end' => Carbon::create(2025, 9, 26, 23, 59, 59),
            'competition_start' => Carbon::create(2025, 10, 13, 8, 0, 0),
            'competition_end' => Carbon::create(2025, 10, 27, 17, 0, 0),
            'webinar_date' => Carbon::create(2025, 9, 27, 19, 0, 0), // Technical Meeting
            'round1_date' => Carbon::create(2025, 10, 13, 8, 0, 0), // Preliminary Day 1
            'round2_date' => Carbon::create(2025, 10, 14, 8, 0, 0), // Preliminary Day 2
            'semifinal_date' => Carbon::create(2025, 10, 15, 8, 0, 0), // Semifinal
            'final_date' => Carbon::create(2025, 10, 27, 8, 0, 0), // Final Round
            'result_announcement' => Carbon::create(2025, 11, 10, 14, 0, 0), // Award Ceremony
            'is_active' => true,
            'is_team_competition' => false, // Individual competition
            'allow_individual' => true,
            'rules' => json_encode([
                'Peserta merupakan mahasiswa/i aktif program sarjana yang terdaftar di PDDikti (Pangkalan Data Pendidikan Tinggi) untuk seluruh universitas negeri dan swasta di Indonesia dari berbagai program studi',
                'Peserta harus melampirkan Kartu Tanda Mahasiswa (KTM) dan Surat Keterangan Aktif Kuliah',
                'Peserta harus mengikuti seluruh rangkaian kegiatan kompetisi',
                'Format debat menggunakan Asian Parliamentary Debate Format',
                'Bahasa yang digunakan adalah bahasa Inggris',
                'Setiap peserta akan mendapat motion/topik debat sebelum pertandingan dimulai',
                'Waktu persiapan (preparation time) adalah 15 menit',
                'Waktu berbicara untuk setiap pembicara adalah 7 menit',
                'Point of Information (POI) diperbolehkan dan berlangsung selama maksimal 15 detik',
                'Keputusan juri bersifat final dan tidak dapat diganggu gugat',
                'Peserta wajib menggunakan pakaian formal selama kompetisi',
                'Peserta dilarang menggunakan perangkat elektronik selama debat berlangsung'
            ]),
            'prizes' => json_encode([
                'champion' => 'Rp 5.000.000 + Trophy + Certificate + Merchandise',
                'runner_up' => 'Rp 3.000.000 + Trophy + Certificate + Merchandise',
                'second_runner_up' => 'Rp 2.000.000 + Trophy + Certificate + Merchandise',
                'best_speaker' => 'Rp 1.000.000 + Certificate + Merchandise'
            ]),
            'contact_person_name' => 'Tim EDC UNAS FEST 2025',
            'contact_person_whatsapp' => '+62 882-1944-5100',
            'guidelines' => 'Individual English debate competition using Asian Parliamentary format for university students across Indonesia.',
            'submission_formats' => json_encode([
                'document' => ['pdf'],
                'image' => ['jpg', 'png']
            ])
        ]);
        $this->createEDCRequirements($competition);
        $this->createEDCCriteria($competition);
    }

    // KDBI Competition based on REVISI 3 PDF
    private function createKDBICompetition()
    {
        $competition = Competition::create([
            'name' => 'Kompetisi Debat Bahasa Indonesia (KDBI)',
            'slug' => 'kdbi-2025',
            'description' => 'Kompetisi debat bahasa Indonesia tingkat nasional yang bertujuan mengasah kemampuan argumentasi, analisis kritis, dan komunikasi publik mahasiswa dalam menghadapi isu-isu kontemporer menggunakan bahasa Indonesia.',
            'category' => 'event_debate',
            'theme' => 'Membangun Indonesia Melalui Dialog Konstruktif',
            'price' => 300000, // Phase 2 price (highest)
            'early_bird_price' => 150000, // Early bird price
            'phase1_price' => 250000, // Phase 1 price
            'price_unas_student' => 150000,
            'price_external_student' => 300000,
            'max_participants' => 48, // 24 teams = 48 participants (2 per team)
            'min_team_members' => 2,
            'max_team_members' => 2,
            'registration_start' => Carbon::create(2025, 8, 25, 0, 0, 0), // Early bird start
            'registration_end' => Carbon::create(2025, 9, 26, 23, 59, 59),   // Phase 2 end
            'early_bird_deadline' => Carbon::create(2025, 8, 31, 23, 59, 59), // Early bird end
            'phase1_deadline' => Carbon::create(2025, 9, 13, 23, 59, 59), // Phase 1 end
            'webinar_date' => Carbon::create(2025, 9, 27, 19, 0, 0), // Technical Meeting
            'competition_start' => Carbon::create(2025, 10, 13, 8, 0, 0),   // Preliminary Day 1
            'competition_end' => Carbon::create(2025, 10, 27, 17, 0, 0),     // Final Round
            'round1_date' => Carbon::create(2025, 10, 13, 8, 0, 0),         // Preliminary Day 1
            'round2_date' => Carbon::create(2025, 10, 14, 8, 0, 0),         // Preliminary Day 2
            'semifinal_date' => Carbon::create(2025, 10, 15, 8, 0, 0),      // Semifinal
            'final_date' => Carbon::create(2025, 10, 27, 8, 0, 0),          // Final Round
            'result_announcement' => Carbon::create(2025, 11, 10, 14, 0, 0), // Award Ceremony
            'is_active' => true,
            'is_team_competition' => true,
            'allow_individual' => false,
            'rules' => json_encode([
                'Peserta merupakan mahasiswa/i aktif program sarjana yang terdaftar di PDDikti (Pangkalan Data Pendidikan Tinggi) untuk seluruh universitas negeri dan swasta di Indonesia dari berbagai program studi',
                'Tim terdiri dari 2 orang mahasiswa dari institusi yang sama',
                'Peserta harus melampirkan Kartu Tanda Mahasiswa (KTM) dan Surat Keterangan Aktif Kuliah',
                'Format debat menggunakan Asian Parliamentary Debate Format dengan modifikasi untuk bahasa Indonesia',
                'Bahasa yang digunakan adalah bahasa Indonesia yang baik dan benar',
                'Setiap tim akan mendapat motion/topik debat sebelum pertandingan dimulai',
                'Waktu persiapan (preparation time) adalah 15 menit',
                'Waktu berbicara untuk setiap pembicara adalah 7 menit',
                'Point of Information (POI) diperbolehkan dan berlangsung selama maksimal 15 detik',
                'Keputusan juri bersifat final dan tidak dapat diganggu gugat',
                'Peserta wajib menggunakan pakaian formal selama kompetisi'
            ]),
            'prizes' => json_encode([
                'juara_1' => 'Rp 6.000.000 + Trophy + Certificate + Merchandise',
                'juara_2' => 'Rp 4.000.000 + Trophy + Certificate + Merchandise',
                'juara_3' => 'Rp 2.500.000 + Trophy + Certificate + Merchandise',
                'best_speaker' => 'Rp 1.500.000 + Certificate + Merchandise'
            ]),
            'contact_person_name' => 'Tim KDBI UNAS FEST 2025',
            'contact_person_whatsapp' => '+62 882-1944-5100',
            'guidelines' => 'Team debate competition in Indonesian language using modified Asian Parliamentary format for university students.',
            'submission_formats' => json_encode([
                'document' => ['pdf'],
                'image' => ['jpg', 'png']
            ])
        ]);
        $this->createKDBIRequirements($competition);
        $this->createKDBICriteria($competition);
    }

    // DCC Infographics Competition based on B.Ing Infografis PDF
    private function createDCCInfographicsCompetition()
    {
        $competition = Competition::create([
            'name' => 'Digital Content Competition - Infographics',
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
            'contact_person_name' => 'Tim DCC Infographics UNAS FEST 2025',
            'contact_person_whatsapp' => '+62 882-1944-5100',
            'guidelines' => 'Infographics competition for high school students in JABODETABEK area with 3-member teams.',
            'submission_formats' => json_encode([
                'image' => ['jpg', 'png'],
                'document' => ['pdf']
            ])
        ]);
        $this->createDCCInfographicsRequirements($competition);
        $this->createDCCInfographicsCriteria($competition);
    }

    // DCC Short Video Competition based on Short Video PDF
    private function createDCCShortVideoCompetition()
    {
        $competition = Competition::create([
            'name' => 'Digital Content Competition - Short Video',
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
                'Participants must follow the competition theme as determined by the committee',
                'Video duration must be between 15 seconds to 3 minutes',
                'Submitted works must be the original creation of the participants, not plagiarized, and must not have been submitted or published in other competitions',
                'Works must not contain elements of SARA (ethnic, religious, racial, and inter-group issues), violence, pornography, hate speech, foul language, or other content that violates the norms, ethics, and laws applicable in Indonesia',
                'Participants are required to upload their works via specified social media platforms (YouTube, Instagram, or TikTok) including the official competition hashtag and tagging the official UNAS FEST account',
                'The social media account used for uploading must be public (not private) throughout the competition period',
                'Video quality must be at least Full HD (1920x1080) resolution',
                'The use of AI-based software for video creation is prohibited',
                'Videos should include clear audio and visual elements that support the message delivery',
                'The judges\' decisions are final, binding, and cannot be contested'
            ]),
            'prizes' => json_encode([
                'juara_1' => 'Champion Prize + Trophy + Certificate + Merchandise',
                'juara_2' => 'Runner-up Prize + Trophy + Certificate + Merchandise', 
                'juara_3' => '3rd Place Prize + Trophy + Certificate + Merchandise'
            ]),
            'contact_person_name' => 'Tim DCC Short Video UNAS FEST 2025',
            'contact_person_whatsapp' => '+62 882-1944-5100',
            'guidelines' => 'Short video competition for high school students in JABODETABEK area with 3-member teams.',
            'submission_formats' => json_encode([
                'video' => ['mp4', 'mov', 'avi'],
                'document' => ['pdf']
            ])
        ]);
        $this->createDCCShortVideoRequirements($competition);
        $this->createDCCShortVideoCriteria($competition);
    }

    // SPC Requirements based on PDF
    private function createSPCRequirements($competition)
    {
        $requirements = [
            [
                'type' => 'document',
                'title' => 'Kartu Tanda Mahasiswa (KTM)',
                'description' => 'Scan KTM yang masih berlaku dari seluruh anggota tim',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg', 'png'],
                'max_size' => 2048,
                'sort_order' => 1
            ],
            [
                'type' => 'document', 
                'title' => 'Surat Keterangan Aktif Kuliah',
                'description' => 'Surat keterangan mahasiswa aktif dari perguruan tinggi masing-masing anggota tim',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size' => 2048,
                'sort_order' => 2
            ],
            [
                'type' => 'document',
                'title' => 'Karya Tulis Ilmiah',
                'description' => 'File karya tulis ilmiah dalam format PDF sesuai template yang disediakan (maksimal 20 halaman)',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size' => 10240,
                'sort_order' => 3
            ],
            [
                'type' => 'document',
                'title' => 'Hasil Cek Plagiasi',
                'description' => 'Hasil screenshot cek plagiasi dengan tingkat similarity maksimal 20%',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg', 'png'],
                'max_size' => 2048,
                'sort_order' => 4
            ],
            [
                'type' => 'text',
                'title' => 'Link Video Presentasi',
                'description' => 'Link video presentasi karya tulis ilmiah (YouTube/Google Drive) durasi maksimal 10 menit',
                'is_required' => false,
                'sort_order' => 5
            ]
        ];

        foreach ($requirements as $req) {
            CompetitionRequirement::create(array_merge($req, ['competition_id' => $competition->id]));
        }
    }

    private function createSPCCriteria($competition)
    {
        $criteria = [
            ['name' => 'Kesesuaian dengan Tema', 'percentage' => 20, 'sort_order' => 1],
            ['name' => 'Originalitas dan Inovasi', 'percentage' => 25, 'sort_order' => 2],
            ['name' => 'Metodologi Penelitian', 'percentage' => 20, 'sort_order' => 3],
            ['name' => 'Analisis dan Pembahasan', 'percentage' => 20, 'sort_order' => 4],
            ['name' => 'Tata Bahasa dan Format Penulisan', 'percentage' => 15, 'sort_order' => 5]
        ];

        foreach ($criteria as $criterion) {
            CompetitionCriteria::create(array_merge($criterion, ['competition_id' => $competition->id]));
        }
    }

    // EDC Requirements based on REVISION 5 PDF  
    private function createEDCRequirements($competition)
    {
        $requirements = [
            [
                'type' => 'document',
                'title' => 'Student ID Card (KTM)',
                'description' => 'Valid student ID card showing active enrollment status',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg', 'png'],
                'max_size' => 2048,
                'sort_order' => 1
            ],
            [
                'type' => 'document',
                'title' => 'Certificate of Active Study',
                'description' => 'Official certificate from university confirming active student status',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size' => 2048,
                'sort_order' => 2
            ],
            [
                'type' => 'document',
                'title' => 'Formal Photo',
                'description' => 'Formal photo for competition identification',
                'is_required' => true,
                'file_types' => ['jpg', 'png'],
                'max_size' => 1024,
                'sort_order' => 3
            ],
            [
                'type' => 'text',
                'title' => 'Academic Year',
                'description' => 'Current academic year of study',
                'is_required' => true,
                'sort_order' => 4
            ],
            [
                'type' => 'text',
                'title' => 'Student ID Number',
                'description' => 'Official student identification number',
                'is_required' => true,
                'sort_order' => 5
            ],
            [
                'type' => 'text',
                'title' => 'Major/Study Program',
                'description' => 'Field of study or major program',
                'is_required' => true,
                'sort_order' => 6
            ],
            [
                'type' => 'text',
                'title' => 'Semester',
                'description' => 'Current semester level',
                'is_required' => true,
                'sort_order' => 7
            ]
        ];

        foreach ($requirements as $req) {
            CompetitionRequirement::create(array_merge($req, ['competition_id' => $competition->id]));
        }
    }

    private function createEDCCriteria($competition)
    {
        $criteria = [
            ['name' => 'Content and Arguments', 'percentage' => 30, 'sort_order' => 1],
            ['name' => 'Delivery and Manner', 'percentage' => 25, 'sort_order' => 2],
            ['name' => 'Language and Grammar', 'percentage' => 20, 'sort_order' => 3],
            ['name' => 'Response to Points of Information', 'percentage' => 15, 'sort_order' => 4],
            ['name' => 'Overall Performance', 'percentage' => 10, 'sort_order' => 5]
        ];

        foreach ($criteria as $criterion) {
            CompetitionCriteria::create(array_merge($criterion, ['competition_id' => $competition->id]));
        }
    }

    // KDBI Requirements based on REVISI 3 PDF
    private function createKDBIRequirements($competition)
    {
        $requirements = [
            [
                'type' => 'document',
                'title' => 'Kartu Tanda Mahasiswa (KTM)',
                'description' => 'KTM yang masih berlaku dari seluruh anggota tim (2 orang)',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg', 'png'],
                'max_size' => 2048,
                'sort_order' => 1
            ],
            [
                'type' => 'document',
                'title' => 'Surat Keterangan Aktif Kuliah',
                'description' => 'Surat keterangan mahasiswa aktif dari perguruan tinggi untuk seluruh anggota tim',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size' => 2048,
                'sort_order' => 2
            ],
            [
                'type' => 'document',
                'title' => 'Foto Formal Tim',
                'description' => 'Foto formal tim untuk identifikasi kompetisi',
                'is_required' => true,
                'file_types' => ['jpg', 'png'],
                'max_size' => 1024,
                'sort_order' => 3
            ],
            [
                'type' => 'text',
                'title' => 'Nama Tim',
                'description' => 'Nama tim yang akan digunakan dalam kompetisi',
                'is_required' => true,
                'sort_order' => 4
            ]
        ];

        foreach ($requirements as $req) {
            CompetitionRequirement::create(array_merge($req, ['competition_id' => $competition->id]));
        }
    }

    private function createKDBICriteria($competition)
    {
        $criteria = [
            ['name' => 'Konten dan Argumentasi', 'percentage' => 30, 'sort_order' => 1],
            ['name' => 'Cara Penyampaian dan Manner', 'percentage' => 25, 'sort_order' => 2],
            ['name' => 'Bahasa dan Tata Bahasa', 'percentage' => 20, 'sort_order' => 3],
            ['name' => 'Respon terhadap Point of Information', 'percentage' => 15, 'sort_order' => 4],
            ['name' => 'Performa Keseluruhan', 'percentage' => 10, 'sort_order' => 5]
        ];

        foreach ($criteria as $criterion) {
            CompetitionCriteria::create(array_merge($criterion, ['competition_id' => $competition->id]));
        }
    }

    // DCC Infographics Requirements based on B.Ing Infografis PDF
    private function createDCCInfographicsRequirements($competition)
    {
        $requirements = [
            [
                'type' => 'document',
                'title' => 'Student ID Card',
                'description' => 'Valid student ID card from all team members (high school students)',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg', 'png'],
                'max_size' => 2048,
                'sort_order' => 1
            ],
            [
                'type' => 'document',
                'title' => 'Certificate of Active Study',
                'description' => 'Statement letter of active student status issued by the school for all team members',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size' => 2048,
                'sort_order' => 2
            ],
            [
                'type' => 'document',
                'title' => 'Team Photo',
                'description' => 'Team photo of all 3 members for identification',
                'is_required' => true,
                'file_types' => ['jpg', 'png'],
                'max_size' => 2048,
                'sort_order' => 3
            ],
            [
                'type' => 'document',
                'title' => 'Infographic Design',
                'description' => 'High-resolution infographic design (Full HD, 4:5 ratio recommended)',
                'is_required' => true,
                'file_types' => ['jpg', 'png', 'pdf'],
                'max_size' => 10240,
                'sort_order' => 4
            ],
            [
                'type' => 'text',
                'title' => 'Social Media Upload Link',
                'description' => 'Link to social media post (Instagram/TikTok/YouTube) with competition hashtag',
                'is_required' => true,
                'sort_order' => 5
            ],
            [
                'type' => 'text',
                'title' => 'Design Software Used',
                'description' => 'Name of graphic design software used (no AI-based software allowed)',
                'is_required' => true,
                'sort_order' => 6
            ]
        ];

        foreach ($requirements as $req) {
            CompetitionRequirement::create(array_merge($req, ['competition_id' => $competition->id]));
        }
    }

    private function createDCCInfographicsCriteria($competition)
    {
        $criteria = [
            ['name' => 'Theme Compliance', 'percentage' => 25, 'sort_order' => 1],
            ['name' => 'Visual Design and Creativity', 'percentage' => 25, 'sort_order' => 2],
            ['name' => 'Information Clarity', 'percentage' => 20, 'sort_order' => 3],
            ['name' => 'Technical Quality', 'percentage' => 15, 'sort_order' => 4],
            ['name' => 'Social Media Impact', 'percentage' => 15, 'sort_order' => 5]
        ];

        foreach ($criteria as $criterion) {
            CompetitionCriteria::create(array_merge($criterion, ['competition_id' => $competition->id]));
        }
    }

    // DCC Short Video Requirements based on Short Video PDF
    private function createDCCShortVideoRequirements($competition)
    {
        $requirements = [
            [
                'type' => 'document',
                'title' => 'Student ID Card',
                'description' => 'Valid student ID card from all team members (high school students)',
                'is_required' => true,
                'file_types' => ['pdf', 'jpg', 'png'],
                'max_size' => 2048,
                'sort_order' => 1
            ],
            [
                'type' => 'document',
                'title' => 'Certificate of Active Study',
                'description' => 'Statement letter of active student status issued by the school for all team members',
                'is_required' => true,
                'file_types' => ['pdf'],
                'max_size' => 2048,
                'sort_order' => 2
            ],
            [
                'type' => 'document',
                'title' => 'Team Photo',
                'description' => 'Team photo of all 3 members for identification',
                'is_required' => true,
                'file_types' => ['jpg', 'png'],
                'max_size' => 2048,
                'sort_order' => 3
            ],
            [
                'type' => 'document',
                'title' => 'Short Video File',
                'description' => 'Short video file (15 seconds - 3 minutes, Full HD quality)',
                'is_required' => true,
                'file_types' => ['mp4', 'mov', 'avi'],
                'max_size' => 51200, // 50MB
                'sort_order' => 4
            ],
            [
                'type' => 'text',
                'title' => 'Social Media Upload Link',
                'description' => 'Link to social media post (Instagram/TikTok/YouTube) with competition hashtag',
                'is_required' => true,
                'sort_order' => 5
            ],
            [
                'type' => 'text',
                'title' => 'Video Editing Software Used',
                'description' => 'Name of video editing software used (no AI-based software allowed)',
                'is_required' => true,
                'sort_order' => 6
            ],
            [
                'type' => 'text',
                'title' => 'Video Duration',
                'description' => 'Exact duration of the video (must be between 15 seconds to 3 minutes)',
                'is_required' => true,
                'sort_order' => 7
            ]
        ];

        foreach ($requirements as $req) {
            CompetitionRequirement::create(array_merge($req, ['competition_id' => $competition->id]));
        }
    }

    private function createDCCShortVideoCriteria($competition)
    {
        $criteria = [
            ['name' => 'Theme Compliance', 'percentage' => 25, 'sort_order' => 1],
            ['name' => 'Creativity and Originality', 'percentage' => 25, 'sort_order' => 2],
            ['name' => 'Message Delivery', 'percentage' => 20, 'sort_order' => 3],
            ['name' => 'Technical Quality (Video/Audio)', 'percentage' => 15, 'sort_order' => 4],
            ['name' => 'Social Media Impact', 'percentage' => 15, 'sort_order' => 5]
        ];

        foreach ($criteria as $criterion) {
            CompetitionCriteria::create(array_merge($criterion, ['competition_id' => $competition->id]));
        }
    }
}