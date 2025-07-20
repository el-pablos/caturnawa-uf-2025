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
                'description' => 'Kompetisi debat bahasa Indonesia tingkat nasional untuk mahasiswa.',
                'category' => 'event_debate',
                'price' => 150000,
                'early_bird_price' => 120000,
                'max_participants' => 32,
                'registration_start' => Carbon::now()->subDays(30),
                'registration_end' => Carbon::now()->addDays(30),
                'competition_start' => Carbon::now()->addDays(45),
                'competition_end' => Carbon::now()->addDays(47),
                'is_active' => true,
                'rules' => 'Aturan kompetisi debat bahasa Indonesia.',
                'prizes' => json_encode([
                    'first' => 'Rp 5.000.000',
                    'second' => 'Rp 3.000.000',
                    'third' => 'Rp 2.000.000'
                ])
            ],
            [
                'name' => 'English Debate Competition (EDC)',
                'slug' => 'edc-2025',
                'description' => 'Kompetisi debat bahasa Inggris tingkat nasional.',
                'category' => 'event_debate',
                'price' => 175000,
                'early_bird_price' => 140000,
                'max_participants' => 24,
                'registration_start' => Carbon::now()->subDays(25),
                'registration_end' => Carbon::now()->addDays(35),
                'competition_start' => Carbon::now()->addDays(50),
                'competition_end' => Carbon::now()->addDays(52),
                'is_active' => true,
                'rules' => 'English debate competition rules.',
                'prizes' => json_encode([
                    'first' => 'Rp 6.000.000',
                    'second' => 'Rp 4.000.000',
                    'third' => 'Rp 2.500.000'
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
                'name' => 'Kompetisi Fotografi',
                'slug' => 'fotografi-2025',
                'description' => 'Kompetisi fotografi dengan tema alam dan budaya Indonesia.',
                'category' => 'event_dcc',
                'price' => 75000,
                'early_bird_price' => 60000,
                'max_participants' => 100,
                'registration_start' => Carbon::now()->subDays(15),
                'registration_end' => Carbon::now()->addDays(45),
                'competition_start' => Carbon::now()->addDays(60),
                'competition_end' => Carbon::now()->addDays(62),
                'is_active' => true,
                'rules' => 'Aturan kompetisi fotografi.',
                'prizes' => json_encode([
                    'first' => 'Rp 3.000.000',
                    'second' => 'Rp 2.000.000',
                    'third' => 'Rp 1.000.000'
                ])
            ],
            [
                'name' => 'Kompetisi Karya Ilmiah',
                'slug' => 'karya-ilmiah-2025',
                'description' => 'Kompetisi penulisan karya ilmiah untuk mahasiswa.',
                'category' => 'event_scientific_paper',
                'price' => 125000,
                'early_bird_price' => 100000,
                'max_participants' => 40,
                'registration_start' => Carbon::now()->subDays(10),
                'registration_end' => Carbon::now()->addDays(50),
                'competition_start' => Carbon::now()->addDays(65),
                'competition_end' => Carbon::now()->addDays(67),
                'is_active' => true,
                'rules' => 'Aturan kompetisi karya ilmiah.',
                'prizes' => json_encode([
                    'first' => 'Rp 5.000.000',
                    'second' => 'Rp 3.500.000',
                    'third' => 'Rp 2.000.000'
                ])
            ]
        ];

        foreach ($competitions as $competition) {
            Competition::create($competition);
            $this->command->info("Competition '{$competition['name']}' created successfully.");
        }
    }
}
