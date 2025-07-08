<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PricingPhase;
use Carbon\Carbon;

class PricingPhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing pricing phases
        PricingPhase::truncate();

        // Define pricing structure for 4 phases with 3 participant categories
        // All prices are in IDR with thousands format (e.g., 100 = Rp 100.000)
        $pricingData = [
            // Early Bird Phase
            [
                'phase_name' => 'early_bird',
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(7),
                'prices' => [
                    'unas_student' => 100000,      // Rp 100.000
                    'external_student' => 200000,  // Rp 200.000
                    'high_school_student' => 50000, // Rp 50.000
                ]
            ],
            // Phase 1
            [
                'phase_name' => 'phase_1',
                'start_date' => now()->addDays(8),
                'end_date' => now()->addDays(21),
                'prices' => [
                    'unas_student' => 250000,      // Rp 250.000
                    'external_student' => 300000,  // Rp 300.000
                    'high_school_student' => 100000, // Rp 100.000
                ]
            ],
            // Phase 2
            [
                'phase_name' => 'phase_2',
                'start_date' => now()->addDays(22),
                'end_date' => now()->addDays(35),
                'prices' => [
                    'unas_student' => 300000,      // Rp 300.000
                    'external_student' => 350000,  // Rp 350.000
                    'high_school_student' => 125000, // Rp 125.000
                ]
            ],
            // Phase 3
            [
                'phase_name' => 'phase_3',
                'start_date' => now()->addDays(36),
                'end_date' => now()->addDays(49),
                'prices' => [
                    'unas_student' => 250000,      // Rp 250.000
                    'external_student' => 300000,  // Rp 300.000
                    'high_school_student' => 100000, // Rp 100.000
                ]
            ],
        ];

        foreach ($pricingData as $phaseData) {
            foreach ($phaseData['prices'] as $category => $amount) {
                PricingPhase::create([
                    'phase_name' => $phaseData['phase_name'],
                    'participant_category' => $category,
                    'amount' => $amount,
                    'start_date' => $phaseData['start_date'],
                    'end_date' => $phaseData['end_date'],
                    'is_active' => true,
                    'description' => $this->getPhaseDescription($phaseData['phase_name'], $category, $amount),
                ]);
            }
        }

        $this->command->info('Pricing phases seeded successfully!');
        $this->command->info('Created ' . PricingPhase::count() . ' pricing phase records.');
    }

    /**
     * Generate description for pricing phase
     *
     * @param string $phaseName
     * @param string $category
     * @param int $amount
     * @return string
     */
    private function getPhaseDescription($phaseName, $category, $amount)
    {
        $phaseNames = [
            'early_bird' => 'Early Bird',
            'phase_1' => 'Phase 1',
            'phase_2' => 'Phase 2',
            'phase_3' => 'Phase 3',
        ];

        $categoryNames = [
            'unas_student' => 'Mahasiswa UNAS',
            'external_student' => 'Mahasiswa Eksternal',
            'high_school_student' => 'Siswa SMA/SMK',
        ];

        $phaseName = $phaseNames[$phaseName] ?? $phaseName;
        $categoryName = $categoryNames[$category] ?? $category;

        return "Harga {$phaseName} untuk {$categoryName}: Rp " . number_format($amount, 0, ',', '.');
    }
}
