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

        // Compute participants per competition
        $this->computeParticipantsPerCompetition();
        $this->info('✓ Participants per competition computed');

        // Compute universities list
        $this->computeUniversitiesList();
        $this->info('✓ Universities list computed');

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

    /**
     * Compute participants per competition
     */
    private function computeParticipantsPerCompetition()
    {
        $competitions = Competition::withCount(['registrations' => function ($query) {
            $query->where('status', 'confirmed');
        }])->get();

        $data = $competitions->map(function ($competition) {
            return [
                'id' => $competition->id,
                'name' => $competition->name,
                'category' => $competition->category,
                'participants_count' => $competition->registrations_count,
                'registration_start' => $competition->registration_start,
                'registration_end' => $competition->registration_end,
            ];
        })->toArray();

        Achievement::updateData(
            'participants_per_competition',
            'Peserta per Kompetisi',
            $data,
            'Jumlah peserta yang terdaftar di setiap kompetisi'
        );
    }

    /**
     * Compute universities list
     */
    private function computeUniversitiesList()
    {
        $universities = Registration::where('status', 'confirmed')
            ->whereNotNull('institution')
            ->select('institution')
            ->selectRaw('COUNT(*) as participants_count')
            ->groupBy('institution')
            ->orderBy('participants_count', 'desc')
            ->get();

        $data = $universities->map(function ($university) {
            return [
                'name' => $university->institution,
                'participants_count' => $university->participants_count,
            ];
        })->toArray();

        Achievement::updateData(
            'universities_list',
            'Daftar Universitas',
            $data,
            'Daftar universitas yang berpartisipasi beserta jumlah peserta'
        );
    }
}
