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
                'slug' => 'kdbi-2025',
                'description' => 'Kompetisi debat bahasa Indonesia tingkat nasional sesuai standar KDBI UNAS FEST 2025.',
                'category' => 'event_debate',
                'theme' => 'UNAS FEST 2025',
                'price' => 300000, // Phase 2 price (highest)
                'early_bird_price' => 150000, // Early bird price
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
                'competition_start' => Carbon::parse('2025-10-13'),   // Preliminary Day 1
                'competition_end' => Carbon::parse('2025-10-27'),     // Final Round
                'round1_date' => Carbon::parse('2025-10-13'),         // Preliminary Day 1
                'semifinal_date' => Carbon::parse('2025-10-15'),      // Semifinal
                'final_date' => Carbon::parse('2025-10-27'),          // Final Round
                'result_announcement' => Carbon::parse('2025-11-10'), // Award Ceremony
                'is_active' => true,
                'rules' => json_encode([
                    'Peserta merupakan mahasiswa/i aktif program sarjana yang terdaftar di PDDikti untuk seluruh universitas negeri dan swasta di Indonesia',
                    'Tim terdiri dari 2 individu (First Speaker & Second Speaker)',
                    'Peserta berasal dari universitas yang sama, diperbolehkan beda program studi, fakultas, atau semester',
                    'Nama tim harus sesuai dengan tema UNAS FEST 2025 tanpa menyinggung unsur SARA',
                    'Debat dilakukan secara daring menggunakan Zoom Meeting yang disediakan panitia',
                    'Alur kompetisi: 24 tim → 12 tim → 4 tim',
                    'Penilaian meliputi Verbal Adjudication dan Silent Round',
                    'Sistem Reset Point - poin dari babak penyisihan tidak dibawa ke final'
                ]),
                'prizes' => json_encode([
                    'juara_1' => 'Trofi Juara 1 + Sertifikat + Hadiah Uang',
                    'juara_2' => 'Trofi Juara 2 + Sertifikat + Hadiah Uang', 
                    'juara_3' => 'Trofi Juara 3 + Sertifikat + Hadiah Uang',
                    'best_speaker' => 'Penghargaan Best Speaker + Sertifikat + Hadiah Uang'
                ]),
                'requirements' => json_encode([
                    'Status mahasiswa/i aktif program sarjana',
                    'Terdaftar di PDDikti (Pangkalan Data Pendidikan Tinggi)',
                    'Kartu mahasiswa dan transkrip nilai',
                    'Akun Zoom dengan koneksi internet stabil',
                    'Dokumen pendaftaran lengkap'
                ])
            ],
            [
                'name' => 'English Debate Competition (EDC)',
                'slug' => 'edc-2025',
                'description' => 'Kompetisi debat bahasa Inggris tingkat nasional sesuai standar EDC UNAS FEST 2025.',
                'category' => 'event_debate',
                'theme' => 'UNAS FEST 2025',
                'price' => 300000, // Phase 2 price (highest)
                'early_bird_price' => 150000, // Early bird price
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
                'competition_start' => Carbon::parse('2025-10-13'),   // Preliminary Day 1
                'competition_end' => Carbon::parse('2025-10-27'),     // Final Round
                'round1_date' => Carbon::parse('2025-10-13'),         // Preliminary Day 1
                'semifinal_date' => Carbon::parse('2025-10-15'),      // Semifinal
                'final_date' => Carbon::parse('2025-10-27'),          // Final Round
                'result_announcement' => Carbon::parse('2025-11-10'), // Award Ceremony
                'is_active' => true,
                'rules' => json_encode([
                    'Participants are active undergraduate students registered in PDDikti for Indonesian universities',
                    'Teams consist of exactly two individuals (First Speaker & Second Speaker)',
                    'Participants must be from the same university but can be from different programs',
                    'Team names must relate to UNAS FEST 2025 theme without SARA elements',
                    'Debates conducted online via Zoom Meeting with provided links',
                    'Tournament progression: 24 teams → 12 teams → 4 teams',
                    'Assessment includes Verbal Adjudication and Silent Rounds',
                    'Reset Point system - preliminary points not carried to final'
                ]),
                'prizes' => json_encode([
                    'champion' => 'Champion Trophy + Certificate + Cash Prize',
                    'runner_up' => 'Runner-up Trophy + Certificate + Cash Prize', 
                    'third_place' => '3rd Place Trophy + Certificate + Cash Prize',
                    'best_speaker' => 'Best Speaker Award + Certificate + Cash Prize'
                ]),
                'requirements' => json_encode([
                    'Active undergraduate student status',
                    'Registered in PDDikti database',
                    'Student ID card and transcript',
                    'Zoom account with stable internet',
                    'TOEFL/IELTS score (recommended)',
                    'Complete registration documents'
                ])
            ],
            [
                'name' => 'Short Movie Competition',
                'slug' => 'short-movie-2025',
                'description' => 'Kompetisi film pendek dengan tema kreativitas dan inovasi.',
                'category' => 'event_dcc',
                'price' => 100000,
                'early_bird_price' => 80000,
                'max_participants' => 50,
                'registration_start' => Carbon::now()->subDays(20),
                'registration_end' => Carbon::now()->addDays(40),
                'competition_start' => Carbon::now()->addDays(55),
                'competition_end' => Carbon::now()->addDays(57),
                'is_active' => true,
                'rules' => 'Aturan kompetisi film pendek.',
                'prizes' => json_encode([
                    'first' => 'Rp 4.000.000',
                    'second' => 'Rp 2.500.000',
                    'third' => 'Rp 1.500.000'
                ])
            ],
            [
                'name' => 'Kompetisi Infografis',
                'slug' => 'infografis-2025',
                'description' => 'Kompetisi infografis dengan tema alam dan budaya Indonesia.',
                'category' => 'event_dcc',
                'price' => 75000,
                'early_bird_price' => 60000,
                'max_participants' => 100,
                'registration_start' => Carbon::now()->subDays(15),
                'registration_end' => Carbon::now()->addDays(45),
                'competition_start' => Carbon::now()->addDays(60),
                'competition_end' => Carbon::now()->addDays(62),
                'is_active' => true,
                'rules' => 'Aturan kompetisi infografis.',
                'prizes' => json_encode([
                    'first' => 'Rp 3.000.000',
                    'second' => 'Rp 2.000.000',
                    'third' => 'Rp 1.000.000'
                ])
            ],
            [
                'name' => 'Scientific Paper Competition (SPC)',
                'slug' => 'spc-2025',
                'description' => 'Kompetisi penulisan karya ilmiah tingkat nasional untuk mahasiswa dengan standar akademik internasional.',
                'category' => 'event_scientific_paper',
                'theme' => 'Innovation and Technology for Sustainable Development',
                'price' => 100000, // Regular price
                'early_bird_price' => 75000, // Early bird price
                'price_unas_student' => 75000,
                'price_external_student' => 100000,
                'max_participants' => 120, // 40 teams x 3 members max
                'min_team_members' => 1,
                'max_team_members' => 3,
                'is_team_competition' => true,
                'allow_individual' => true,
                'registration_start' => Carbon::parse('2025-08-01'), // Early bird start
                'registration_end' => Carbon::parse('2025-09-30'),   // Regular registration end
                'early_bird_deadline' => Carbon::parse('2025-08-31'), // Early bird end
                'submission_start' => Carbon::parse('2025-09-01'),     // Abstract submission start
                'submission_end' => Carbon::parse('2025-10-10'),       // Full paper deadline
                'competition_start' => Carbon::parse('2025-10-15'),    // Review period start
                'competition_end' => Carbon::parse('2025-12-15'),      // Presentation date
                'result_announcement' => Carbon::parse('2025-12-20'),  // Award ceremony
                'is_active' => true,
                'rules' => json_encode([
                    'Peserta merupakan mahasiswa/i aktif program sarjana, magister, atau doktor yang terdaftar di perguruan tinggi Indonesia atau luar negeri',
                    'Karya tulis harus original dan belum pernah dipublikasikan dalam jurnal atau prosiding manapun',
                    'Tim dapat terdiri dari 1-3 orang (individual atau kelompok) dari universitas yang sama atau berbeda',
                    'Mengikuti format penulisan ilmiah yang telah ditentukan dengan template yang disediakan panitia',
                    'Naskah ditulis maksimal 15 halaman (tidak termasuk cover, daftar pustaka, dan lampiran)',
                    'Menggunakan referensi minimal 15 sumber (jurnal ilmiah, buku, prosiding) dengan 70% referensi terbaru dari 10 tahun terakhir',
                    'Similarity index maksimal 20% berdasarkan hasil Turnitin, Grammarly, atau tools similarity lainnya',
                    'Menggunakan bahasa Indonesia atau bahasa Inggris yang baik dan benar sesuai kaidah penulisan ilmiah',
                    'Menyertakan abstract dalam bahasa Inggris maksimal 300 kata dengan 5-7 keywords',
                    'Peserta wajib menghadiri sesi presentasi final jika lolos ke tahap tersebut',
                    'Keputusan juri bersifat final dan tidak dapat diganggu gugat',
                    'Panitia berhak mendiskualifikasi peserta yang terbukti melakukan plagiasi atau pelanggaran etika akademik'
                ]),
                'prizes' => json_encode([
                    'best_paper' => 'Best Paper Award + Rp 7.500.000 + Sertifikat + Publikasi',
                    'runner_up_1' => '2nd Best Paper + Rp 5.000.000 + Sertifikat + Publikasi',
                    'runner_up_2' => '3rd Best Paper + Rp 3.000.000 + Sertifikat + Publikasi',
                    'best_presentation' => 'Best Presentation Award + Rp 2.000.000 + Sertifikat',
                    'most_innovative' => 'Most Innovative Research + Rp 2.000.000 + Sertifikat'
                ]),
                'requirements' => json_encode([
                    'Status mahasiswa/i aktif (sarjana/magister/doktor)',
                    'Kartu mahasiswa dan transkrip nilai terbaru',
                    'Surat keterangan aktif kuliah dari fakultas',
                    'Abstract dalam bahasa Inggris (300 kata max)',
                    'Full paper sesuai template (maksimal 15 halaman)',
                    'Laporan similarity check (Turnitin/Grammarly) < 20%',
                    'Surat pernyataan orisinalitas bermaterai',
                    'CV singkat semua anggota tim',
                    'Ethical clearance (jika penelitian melibatkan manusia/hewan)'
                ]),
                'judging_criteria' => json_encode([
                    'Orisinalitas dan Inovasi (30%)' => 'Tingkat kebaruan, kontribusi, dan inovasi dalam penelitian',
                    'Metodologi dan Ketelitian (25%)' => 'Kualitas metode penelitian, analisis data, dan ketelitian ilmiah',
                    'Analisis dan Pembahasan (25%)' => 'Kedalaman analisis, interpretasi hasil, dan diskusi temuan',
                    'Penulisan dan Struktur (20%)' => 'Kualitas penulisan akademik, struktur, dan presentasi'
                ])
            ]
        ];

        foreach ($competitions as $competition) {
            Competition::create($competition);
            $this->command->info("Competition '{$competition['name']}' created successfully.");
        }
    }
}
