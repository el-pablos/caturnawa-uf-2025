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
                'name' => 'Bio Innovation Challenge 2025',
                'slug' => 'bio-innovation-challenge-2025',
                'description' => 'Kompetisi inovasi teknologi untuk pelestarian keanekaragaman hayati dan solusi berkelanjutan untuk lingkungan.',
                'short_description' => 'Inovasi teknologi untuk pelestarian keanekaragaman hayati',
                'category' => 'biodiversity',
                'type' => 'team',
                'theme' => 'Sustainable Biodiversity Solutions',
                'price' => 150000,
                'early_bird_price' => 120000,
                'early_bird_deadline' => Carbon::now()->addDays(30),
                'registration_start' => Carbon::now()->subDays(5),
                'registration_end' => Carbon::now()->addDays(45),
                'competition_start' => Carbon::now()->addDays(50),
                'competition_end' => Carbon::now()->addDays(80),
                'submission_deadline' => Carbon::now()->addDays(75),
                'result_announcement' => Carbon::now()->addDays(85),
                'max_participants' => 200,
                'min_team_members' => 2,
                'max_team_members' => 5,
                'prize_amount' => 25000000,
                'requirements' => [
                    'Mahasiswa aktif S1/D3/D4',
                    'Tim terdiri dari 2-5 orang',
                    'Proposal inovasi teknologi bio',
                    'Prototype atau konsep yang dapat diimplementasikan'
                ],
                'prizes' => [
                    'Juara 1: Rp 15.000.000',
                    'Juara 2: Rp 7.500.000',
                    'Juara 3: Rp 2.500.000'
                ],
                'rules' => [
                    'Karya harus original dan belum pernah dipublikasikan',
                    'Menggunakan teknologi ramah lingkungan',
                    'Fokus pada solusi keanekaragaman hayati',
                    'Presentasi dalam bahasa Indonesia atau Inggris'
                ],
                'contact_person' => 'Dr. Sari Biodiversity',
                'contact_email' => 'bio@unasfest.ac.id',
                'contact_phone' => '+62 21 1234 5678',
                'is_active' => true,
                'is_featured' => true,
                'allow_individual' => false,
                'is_team_competition' => true,
            ],
            [
                'name' => 'Health Tech Innovation 2025',
                'slug' => 'health-tech-innovation-2025',
                'description' => 'Kompetisi pengembangan teknologi kesehatan untuk meningkatkan kualitas hidup dan aksesibilitas layanan kesehatan.',
                'short_description' => 'Teknologi kesehatan untuk meningkatkan kualitas hidup',
                'category' => 'health',
                'type' => 'team',
                'theme' => 'Digital Health Solutions',
                'price' => 175000,
                'early_bird_price' => 140000,
                'early_bird_deadline' => Carbon::now()->addDays(25),
                'registration_start' => Carbon::now()->subDays(3),
                'registration_end' => Carbon::now()->addDays(40),
                'competition_start' => Carbon::now()->addDays(45),
                'competition_end' => Carbon::now()->addDays(75),
                'submission_deadline' => Carbon::now()->addDays(70),
                'result_announcement' => Carbon::now()->addDays(80),
                'max_participants' => 150,
                'min_team_members' => 2,
                'max_team_members' => 4,
                'prize_amount' => 30000000,
                'requirements' => [
                    'Mahasiswa aktif dari berbagai jurusan',
                    'Tim multidisiplin diutamakan',
                    'Proposal solusi teknologi kesehatan',
                    'Prototype aplikasi atau device'
                ],
                'prizes' => [
                    'Juara 1: Rp 20.000.000',
                    'Juara 2: Rp 7.000.000',
                    'Juara 3: Rp 3.000.000'
                ],
                'rules' => [
                    'Solusi harus applicable di Indonesia',
                    'Mempertimbangkan aspek etika medis',
                    'User-friendly dan accessible',
                    'Dapat diimplementasikan dengan teknologi existing'
                ],
                'contact_person' => 'Dr. Ahmad HealthTech',
                'contact_email' => 'health@unasfest.ac.id',
                'contact_phone' => '+62 21 2345 6789',
                'is_active' => true,
                'is_featured' => true,
                'allow_individual' => false,
                'is_team_competition' => true,
            ],
            [
                'name' => 'Future Technology Hackathon',
                'slug' => 'future-technology-hackathon',
                'description' => 'Hackathon 48 jam untuk mengembangkan solusi teknologi masa depan menggunakan AI, IoT, Blockchain, dan teknologi emerging lainnya.',
                'short_description' => 'Hackathon 48 jam untuk teknologi masa depan',
                'category' => 'technology',
                'type' => 'team',
                'theme' => 'AI & Emerging Technologies',
                'price' => 200000,
                'early_bird_price' => 160000,
                'early_bird_deadline' => Carbon::now()->addDays(20),
                'registration_start' => Carbon::now()->subDays(1),
                'registration_end' => Carbon::now()->addDays(35),
                'competition_start' => Carbon::now()->addDays(40),
                'competition_end' => Carbon::now()->addDays(42),
                'submission_deadline' => Carbon::now()->addDays(42),
                'result_announcement' => Carbon::now()->addDays(45),
                'max_participants' => 120,
                'min_team_members' => 3,
                'max_team_members' => 5,
                'prize_amount' => 50000000,
                'requirements' => [
                    'Mahasiswa atau fresh graduate',
                    'Pengalaman programming minimal 1 tahun',
                    'Tim dengan skill complementary',
                    'Laptop dan tools development sendiri'
                ],
                'prizes' => [
                    'Juara 1: Rp 30.000.000',
                    'Juara 2: Rp 15.000.000',
                    'Juara 3: Rp 5.000.000'
                ],
                'rules' => [
                    'Coding dimulai saat hackathon',
                    'Open source libraries diperbolehkan',
                    'Harus menggunakan minimal 1 emerging tech',
                    'Demo dan pitch 10 menit'
                ],
                'contact_person' => 'Prof. Budi TechFuture',
                'contact_email' => 'tech@unasfest.ac.id',
                'contact_phone' => '+62 21 3456 7890',
                'is_active' => true,
                'is_featured' => true,
                'allow_individual' => false,
                'is_team_competition' => true,
            ]
        ];

        foreach ($competitions as $competition) {
            Competition::create($competition);
        }
    }
}
