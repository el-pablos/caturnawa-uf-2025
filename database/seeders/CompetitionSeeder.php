<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Competition;
use Carbon\Carbon;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $competitions = [
            [
                'name' => 'Kompetisi Debat Bahasa Indonesia (KDBI)',
                'name_en' => 'Indonesian Language Debate Competition (KDBI)',
                'slug' => 'kdbi-2025',
                'description' => 'Kompetisi debat bahasa Indonesia tingkat nasional yang bertujuan mengasah kemampuan argumentasi, analisis kritis, dan komunikasi publik mahasiswa dalam menghadapi isu-isu kontemporer.',
                'description_en' => 'National Indonesian language debate competition aimed at enhancing students\' argumentation skills, critical analysis, and public communication in addressing contemporary issues.',
                'category' => 'event_debate',
                'theme' => 'UNAS FEST 2025',
                'price' => 300000, // Phase 2 price (highest)
                'early_bird_price' => 150000, // Early bird price
                'phase1_price' => 250000, // Phase 1 price
                'price_unas_student' => 150000,
                'price_external_student' => 300000,
                'max_participants' => 48, // 24 teams = 48 participants (2 per team)
                'min_team_members' => 2,
                'max_team_members' => 2,
                'is_team_competition' => true,
                'allow_individual' => false,
                'registration_start' => Carbon::parse('2025-08-25'), // Early bird start
                'registration_end' => Carbon::parse('2025-09-26'),   // Phase 2 end
                'early_bird_deadline' => Carbon::parse('2025-08-31'), // Early bird end
                'phase1_deadline' => Carbon::parse('2025-09-13'), // Phase 1 end
                'webinar_date' => Carbon::parse('2025-09-27'), // Technical Meeting
                'competition_start' => Carbon::parse('2025-10-13'),   // Preliminary Day 1
                'competition_end' => Carbon::parse('2025-10-27'),     // Final Round
                'round1_date' => Carbon::parse('2025-10-13'),         // Preliminary Day 1
                'round2_date' => Carbon::parse('2025-10-14'),         // Preliminary Day 2
                'semifinal_date' => Carbon::parse('2025-10-15'),      // Semifinal
                'final_date' => Carbon::parse('2025-10-27'),          // Final Round
                'result_announcement' => Carbon::parse('2025-11-10'), // Award Ceremony
                'is_active' => true,
                'rules' => json_encode([
                    'Peserta merupakan mahasiswa/i aktif program sarjana yang terdaftar di PDDikti (Pangkalan Data Pendidikan Tinggi) untuk seluruh universitas negeri dan swasta di Indonesia dari berbagai program studi',
                    'Tim terdiri dari 2 individu (anggota tim diperbolehkan seluruhnya laki-laki, perempuan, ataupun campuran) dengan posisi pembicara yang tidak dapat diubah hingga kompetisi berakhir',
                    'Peserta berasal dari universitas yang sama, diperbolehkan beda program studi, fakultas, ataupun semester',
                    'Setiap tim wajib untuk membuat nama tim yang sesuai dengan tema UNAS FEST 2025, tanpa menyinggung unsur Suku, Agama, Ras, dan Antar Golongan (SARA)',
                    'Peserta yang telah membayar biaya pendaftaran, kemudian membatalkan keikutsertaannya, maka biaya tersebut tidak dapat dikembalikan',
                    'Peserta wajib mengikuti peraturan yang telah ditentukan dan dicantumkan pada buku pedoman kegiatan KDBI UNAS FEST 2025',
                    'Debat dilakukan secara daring menggunakan Zoom Meeting yang disediakan oleh panitia UNAS FEST 2025',
                    'Alur kompetisi: 24 tim → Penyisihan 2 hari → Semifinal 12 tim → Final 4 tim'
                ]),
                'requirements' => json_encode([
                    'Status mahasiswa/i aktif program sarjana',
                    'Terdaftar di PDDikti (Pangkalan Data Pendidikan Tinggi)',
                    'Kartu Tanda Mahasiswa atau Surat Keterangan Mahasiswa Aktif',
                    'Menghadiri Webinar dan Technical Meeting pada 27 September 2025',
                    'Memiliki akun Zoom dengan koneksi internet stabil untuk debat online',
                    'Mengikuti seluruh tahapan kompetisi sesuai jadwal yang telah ditentukan'
                ])
            ],
            [
                'name' => 'English Debate Competition (EDC)',
                'name_en' => 'English Debate Competition (EDC)',
                'slug' => 'edc-2025',
                'description' => 'Kompetisi debat bahasa Inggris tingkat nasional yang dirancang untuk mengasah kemampuan berpikir kritis, argumentasi, dan komunikasi publik mahasiswa dalam bahasa Inggris.',
                'description_en' => 'National English debate competition designed to enhance students\' critical thinking, argumentation, and public communication skills in English language.',
                'category' => 'event_debate',
                'theme' => 'UNAS FEST 2025',
                'price' => 300000, // Phase 2 price (highest)
                'early_bird_price' => 150000, // Early bird price
                'phase1_price' => 250000, // Phase 1 price
                'price_unas_student' => 150000,
                'price_external_student' => 300000,
                'max_participants' => 48, // 24 teams = 48 participants (2 per team)
                'min_team_members' => 2,
                'max_team_members' => 2,
                'is_team_competition' => true,
                'allow_individual' => false,
                'registration_start' => Carbon::parse('2025-08-25'), // Early bird start
                'registration_end' => Carbon::parse('2025-09-26'),   // Phase 2 end
                'early_bird_deadline' => Carbon::parse('2025-08-31'), // Early bird end
                'phase1_deadline' => Carbon::parse('2025-09-13'), // Phase 1 end
                'webinar_date' => Carbon::parse('2025-09-27'), // Technical Meeting
                'competition_start' => Carbon::parse('2025-10-13'),   // Preliminary Day 1
                'competition_end' => Carbon::parse('2025-10-27'),     // Final Round
                'round1_date' => Carbon::parse('2025-10-13'),         // Preliminary Day 1
                'round2_date' => Carbon::parse('2025-10-14'),         // Preliminary Day 2
                'semifinal_date' => Carbon::parse('2025-10-15'),      // Semifinal
                'final_date' => Carbon::parse('2025-10-27'),          // Final Round
                'result_announcement' => Carbon::parse('2025-11-10'), // Award Ceremony
                'is_active' => true,
                'rules' => json_encode([
                    'Participants are active undergraduate students registered in PDDikti (Pangkalan Data Pendidikan Tinggi) for state and private universities in Indonesia from various study programs',
                    'Participants are teams consisting of two individuals (team members can be male, female, or mixed). The speaker position that participants choose cannot be changed until the competition ends',
                    'Participants come from the same university and can be from different study programs, faculties, or semesters',
                    'All participants must create a group name that relates to the UNAS FEST 2025 theme, without offending any elements of Ethnicity, Religion, Race, and Intergroup (SARA)',
                    'Participants who have paid the registration fee and somehow cancelled their participation will not get a refund',
                    'Participants must follow the rules that have been determined and listed in the EDC UNAS FEST 2025 guidebook',
                    'Debates conducted online via Zoom Meeting using meeting links provided by UNAS FEST 2025 committees',
                    'Tournament progression: 24 teams → Preliminary Round (2 days) → Semifinal (12 teams) → Final Round (4 teams)'
                ]),
                'requirements' => json_encode([
                    'Active undergraduate student status',
                    'Registered in PDDikti (Pangkalan Data Pendidikan Tinggi) database',
                    'Student ID card or Active Student Certificate',
                    'Attend Webinar and Technical Meeting on September 27, 2025',
                    'Zoom account with stable internet connection for online debates',
                    'Follow all competition stages according to the predetermined schedule',
                    'English proficiency (TOEFL/IELTS score recommended but not mandatory)'
                ])
            ],
            [
                'name' => 'Digital Content Competition - Short Video',
                'name_en' => 'Digital Content Competition - Short Video',
                'slug' => 'dcc-short-video-2025',
                'description' => 'Kompetisi video pendek yang bertujuan untuk meningkatkan kesadaran dan partisipasi aktif generasi muda terhadap isu deforestasi dengan mendorong terciptanya solusi kreatif berbasis teknologi, khususnya kecerdasan buatan (AI).',
                'description_en' => 'Short video competition aimed at increasing awareness and active participation of young people on deforestation issues by encouraging the creation of creative solutions based on technology, especially artificial intelligence (AI).',
                'category' => 'event_dcc',
                'theme' => 'Conducting a Preventive Action for Deforestation through AI-assisted Technology in Acquiring a Resilient and Sustainable Ecology',
                'price' => 75000, // Phase 2 price
                'early_bird_price' => 50000, // Early bird price
                'phase1_price' => 65000, // Phase 1 price
                'max_participants' => 45, // 15 teams = 45 participants (3 per team)
                'min_team_members' => 3,
                'max_team_members' => 3,
                'is_team_competition' => true,
                'allow_individual' => false,
                'registration_start' => Carbon::parse('2025-08-25'), // Early bird start
                'registration_end' => Carbon::parse('2025-09-26'),   // Phase 2 end
                'early_bird_deadline' => Carbon::parse('2025-08-31'), // Early bird end
                'phase1_deadline' => Carbon::parse('2025-09-13'), // Phase 1 end
                'webinar_date' => Carbon::parse('2025-09-29'), // Webinar
                'submission_start' => Carbon::parse('2025-10-08'), // Peluncuran Karya
                'submission_end' => Carbon::parse('2025-10-17'),   // Submission deadline
                'competition_start' => Carbon::parse('2025-10-18'), // Penilaian Karya
                'competition_end' => Carbon::parse('2025-10-27'),   // Final presentation
                'result_announcement' => Carbon::parse('2025-11-10'), // Awarding
                'is_active' => true,
                'rules' => json_encode([
                    'Peserta Digital Content Competition UNAS FEST 2025 wajib menyertakan surat keterangan siswa/i aktif yang dikeluarkan oleh pihak sekolah SMA/MA/SMK sederajat di JABODETABEK',
                    'Tim terdiri dari 3 orang siswa/i aktif SMA/MA/SMK sederajat di JABODETABEK',
                    'Karya short video berdurasi maksimum 3 (tiga) menit',
                    'Karya yang diunggah harus merupakan hasil ciptaan asli peserta, bukan hasil plagiarisme, dan belum pernah diikutsertakan atau dipublikasikan dalam kompetisi lain',
                    'Karya tidak diperkenankan mengandung unsur SARA (Suku, Agama, Ras, dan Antargolongan), kekerasan, pornografi, ujaran kebencian, kata-kata kasar, maupun konten lain yang bertentangan dengan norma, etika dan peraturan perundang-undangan yang berlaku di Indonesia',
                    'Peserta diwajibkan mengunggah karya melalui platform media sosial yang telah ditentukan (Youtube, Instagram, atau Tiktok) dengan menyertakan tagar resmi lomba dan mention akun resmi UNAS FEST',
                    'Akun media sosial yang digunakan peserta untuk mengunggah hasil karya wajib bersifat publik (tidak dalam keadaan privat) selama periode kompetisi berlangsung',
                    'Peserta dibebaskan untuk menggunakan software desain grafis apapun, seperti Freehand, Corel Draw, Adobe Photoshop, Canva atau aplikasi serupa dengan ketentuan tidak diperbolehkan menggunakan aplikasi berbasis kecerdasan buatan (AI)',
                    'Alur kompetisi: 15 tim → Semifinal → 7 tim final → Awarding onsite'
                ]),
                'requirements' => json_encode([
                    'Pas Foto 3x4',
                    'Kartu Pelajar',
                    'Surat keterangan siswa/i aktif yang dikeluarkan oleh pihak sekolah',
                    'Bukti Screenshot Follow Instagram UNAS FEST @Unasfest',
                    'Bukti Screenshot Follow TikTok UNAS FEST @Unasfest',
                    'Bukti Screenshot Follow YouTube UNAS FEST @Unasfest',
                    'Bukti Screenshot Mengupload Twibbon (Perwakilan)',
                    'Video maksimal 3 menit dengan tema yang telah ditentukan',
                    'Upload ke platform media sosial (YouTube/Instagram/TikTok) dengan hashtag dan mention resmi'
                ])
            ],
            [
                'name' => 'Digital Content Competition - Infografis',
                'name_en' => 'Digital Content Competition - Infographics',
                'slug' => 'dcc-infografis-2025',
                'description' => 'Kompetisi infografis yang bertujuan untuk meningkatkan kesadaran dan partisipasi aktif generasi muda terhadap isu deforestasi dengan mendorong terciptanya solusi kreatif berbasis teknologi, khususnya kecerdasan buatan (AI).',
                'description_en' => 'Infographics competition aimed at increasing awareness and active participation of young people on deforestation issues by encouraging the creation of creative solutions based on technology, especially artificial intelligence (AI).',
                'category' => 'event_dcc',
                'theme' => 'Conducting a Preventive Action for Deforestation through AI-assisted Technology in Acquiring a Resilient and Sustainable Ecology',
                'price' => 75000, // Phase 2 price
                'early_bird_price' => 50000, // Early bird price
                'phase1_price' => 65000, // Phase 1 price
                'max_participants' => 45, // 15 teams = 45 participants (3 per team)
                'min_team_members' => 3,
                'max_team_members' => 3,
                'is_team_competition' => true,
                'allow_individual' => false,
                'registration_start' => Carbon::parse('2025-08-25'), // Early bird start
                'registration_end' => Carbon::parse('2025-09-26'),   // Phase 2 end
                'early_bird_deadline' => Carbon::parse('2025-08-31'), // Early bird end
                'phase1_deadline' => Carbon::parse('2025-09-13'), // Phase 1 end
                'webinar_date' => Carbon::parse('2025-09-29'), // Webinar
                'submission_start' => Carbon::parse('2025-10-08'), // Peluncuran Karya
                'submission_end' => Carbon::parse('2025-10-17'),   // Submission deadline
                'competition_start' => Carbon::parse('2025-10-18'), // Penilaian Karya
                'competition_end' => Carbon::parse('2025-10-27'),   // Final presentation
                'result_announcement' => Carbon::parse('2025-11-10'), // Awarding
                'is_active' => true,
                'rules' => json_encode([
                    'Peserta Digital Content Competition UNAS FEST 2025 wajib menyertakan surat keterangan siswa/i aktif yang dikeluarkan oleh pihak sekolah SMA/MA/SMK sederajat di JABODETABEK',
                    'Tim terdiri dari 3 orang siswa/i aktif SMA/MA/SMK sederajat di JABODETABEK',
                    'Kualitas desain infographic wajib memiliki resolusi full HD untuk memastikan ketajaman gambar yang optimal serta kesesuaian dengan ketentuan platform yang digunakan',
                    'Karya infographic harus memadukan elemen teks, grafik, ilustrasi, dan ikon yang saling mendukung guna menyampaikan informasi secara jelas, sistematis, dan efektif',
                    'Ukuran desain infographic disarankan dalam rasio 4:5 (potrait) untuk optimalisasi tampilan di media sosial',
                    'Karya yang diunggah harus merupakan hasil ciptaan asli peserta, bukan hasil plagiarisme, dan belum pernah diikutsertakan atau dipublikasikan dalam kompetisi lain',
                    'Karya tidak diperkenankan mengandung unsur SARA (Suku, Agama, Ras, dan Antargolongan), kekerasan, pornografi, ujaran kebencian, kata-kata kasar, maupun konten lain yang bertentangan dengan norma, etika dan peraturan perundang-undangan yang berlaku di Indonesia',
                    'Peserta diwajibkan mengunggah karya melalui platform media sosial yang telah ditentukan (Instagram atau Tiktok) dengan menyertakan tagar resmi lomba dan mention akun resmi UNAS FEST',
                    'Akun media sosial yang digunakan peserta untuk mengunggah hasil karya wajib bersifat publik (tidak dalam keadaan privat) selama periode kompetisi berlangsung',
                    'Peserta dibebaskan untuk menggunakan software desain grafis apapun, seperti Freehand, Corel Draw, Adobe Photoshop, Canva atau aplikasi serupa dengan ketentuan tidak diperbolehkan menggunakan aplikasi berbasis kecerdasan buatan (AI)',
                    'Alur kompetisi: 15 tim → Semifinal → 7 tim final → Awarding onsite'
                ]),
                'requirements' => json_encode([
                    'Pas Foto 3x4',
                    'Kartu Pelajar',
                    'Surat keterangan siswa/i aktif yang dikeluarkan oleh pihak sekolah',
                    'Bukti Screenshot Follow Instagram UNAS FEST @Unasfest',
                    'Bukti Screenshot Follow TikTok UNAS FEST @Unasfest',
                    'Bukti Screenshot Follow YouTube UNAS FEST @Unasfest',
                    'Bukti Screenshot Mengupload Twibbon (Perwakilan)',
                    'Infografis dengan resolusi Full HD, rasio 4:5 (portrait)',
                    'Upload ke platform media sosial (Instagram/TikTok) dengan hashtag dan mention resmi'
                ])
            ],
            [
                'name' => 'Scientific Paper Competition (SPC)',
                'name_en' => 'Scientific Paper Competition (SPC)',
                'slug' => 'spc-2025',
                'description' => 'Scientific Paper Competition UNAS FEST 2025 adalah departemen yang menyelenggarakan lomba karya tulis ilmiah berbasis hasil penelitian melalui metode pengumpulan data seperti observasi, wawancara, kuesioner, dan FGD. Karya tulis disusun secara sistematis, menggunakan Bahasa Indonesia yang baik dan benar, serta mengikuti kaidah ilmiah yang dapat dipertanggungjawabkan.',
                'description_en' => 'Scientific Paper Competition UNAS FEST 2025 is a department that organizes scientific paper competitions based on research results through data collection methods such as observation, interviews, questionnaires, and FGDs. Scientific papers are compiled systematically, using good and correct Indonesian, and follow accountable scientific principles.',
                'category' => 'event_scientific_paper',
                'theme' => 'Innovation and Technology for Sustainable Development',
                'price' => 150000, // Phase 2 price
                'early_bird_price' => 115000, // Early bird price
                'phase1_price' => 135000, // Phase 1 price
                'price_unas_student' => 115000,
                'price_external_student' => 150000,
                'max_participants' => 60, // Individual competition
                'min_team_members' => 1,
                'max_team_members' => 1,
                'is_team_competition' => false,
                'allow_individual' => true,
                'registration_start' => Carbon::parse('2025-08-25'), // Early bird start
                'registration_end' => Carbon::parse('2025-09-26'),   // Phase 2 end
                'early_bird_deadline' => Carbon::parse('2025-08-31'), // Early bird end
                'phase1_deadline' => Carbon::parse('2025-09-13'), // Phase 1 end
                'submission_start' => Carbon::parse('2025-10-08'),     // Pengumpulan Naskah SPC
                'submission_end' => Carbon::parse('2025-10-17'),       // Full paper deadline
                'competition_start' => Carbon::parse('2025-10-18'),    // Penilaian Naskah SPC oleh Dewan Juri
                'competition_end' => Carbon::parse('2025-10-27'),      // Pelaksanaan Babak Final
                'technical_meeting' => Carbon::parse('2025-10-25'),    // Technical Meeting Peserta
                'result_announcement' => Carbon::parse('2025-11-10'),  // Awarding Unas Fest 2025
                'is_active' => true,
                'rules' => json_encode([
                    'Peserta Scientific Paper Competition UNAS FEST 2025 merupakan Mahasiswa/i aktif program Sarjana S1 yang terdaftar di PDDIKTI (Pangkalan Data Pendidikan Tinggi) dari berbagai program studi dan Perguruan Tinggi Negeri maupun Swasta di Indonesia',
                    'Peserta Scientific Paper Competition UNAS FEST 2025 merupakan Mahasiswa/i yang belum pernah memiliki gelar sarjana (S1)',
                    'Peserta Scientific Paper Competition UNAS FEST 2025 bersifat individu bukan kelompok',
                    'Peserta wajib mengunduh buku pedoman kegiatan Scientific Paper Competition UNAS FEST 2025 serta memahami seluruh ketentuan yang tercantum dalam buku pedoman tersebut',
                    'Peserta Scientific Paper Competition UNAS FEST 2025 wajib mengikuti seluruh tahapan kompetisi yang telah ditetapkan oleh panitia penyelenggara, mulai dari tahap awal hingga akhir, termasuk Technical Meeting serta presentasi final (apabila peserta dinyatakan lolos)',
                    'Segala bentuk kecurangan atau pelanggaran tata tertib lainnya akan dikenakan sanksi tegas dari panitia, berupa diskualifikasi dari kompetisi tanpa pengecualian',
                    'Karya tulis disusun secara sistematis menggunakan Bahasa Indonesia yang baik dan benar serta mengikuti kaidah ilmiah yang dapat dipertanggungjawabkan',
                    'Kompetisi bertujuan untuk melahirkan mahasiswa/i yang peduli terhadap isu-isu lingkungan dan sosial, serta memiliki kemampuan analisis yang tajam dan berpikir kritis'
                ]),
                'requirements' => json_encode([
                    'Email Aktif',
                    'Nama Lengkap',
                    'Jenis Kelamin',
                    'Alamat Lengkap',
                    'Nomor WhatsApp Aktif',
                    'Asal Perguruan Tinggi',
                    'Fakultas',
                    'Program Studi',
                    'NPM (Nomor Pokok Mahasiswa)',
                    'Scan Kartu Tanda Mahasiswa / Surat Keterangan Mahasiswa Aktif',
                    'KRS (Kartu Rencana Studi)',
                    'Pas Foto Background Merah (UK. 4x6)',
                    'Upload Bukti Prestasi / Capaian Unggulan (Maksimal 10)',
                    'Upload Surat Pengantar Delegasi',
                    'Bukti Upload Twibbon dan Mengikuti Seluruh Akun Media Sosial Resmi UNAS FEST (Screenshot)',
                    'Judul Karya',
                    'File Karya (Format PDF)',
                    'Deskripsi Karya',
                    'Teknologi Yang Digunakan',
                    'Scan Surat Pernyataan Orisinalitas Karya (Format PDF)',
                    'Scan Surat Pernyataan Pengalihan Hak Cipta (Format PDF)'
                ])
            ]
        ];

        foreach ($competitions as $competition) {
            Competition::create($competition);
            $this->command->info("Competition '{$competition['name']}' created successfully.");
        }
    }
}
