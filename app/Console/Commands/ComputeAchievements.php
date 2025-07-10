<?php

namespace App\Console\Commands;

use App\Models\Achievement;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ComputeAchievements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'achievements:compute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compute and cache achievements data for UNAS Fest';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Computing achievements data...');

        // Compute total competitions
        $this->computeTotalCompetitions();
        $this->info('✓ Total competitions computed');

        // Removed participant-related computations

        // Clear cache to force refresh
        Cache::forget('achievements_data');
        $this->info('✓ Cache cleared');

        $this->info('All achievements data computed successfully!');
    }

    /**
     * Compute total competitions
     */
    private function computeTotalCompetitions()
    {
        $total = Competition::count();
        $active = Competition::where('is_active', true)->count();

        $data = [
            'total' => $total,
            'active' => $active,
            'completed' => $total - $active,
        ];

        Achievement::updateData(
            'total_competitions',
            'Total Kompetisi',
            $data,
            'Jumlah total kompetisi yang telah diselenggarakan'
        );
    }




}
