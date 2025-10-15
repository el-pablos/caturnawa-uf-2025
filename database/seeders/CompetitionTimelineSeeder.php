<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\CompetitionTimeline;
use Illuminate\Database\Seeder;

class CompetitionTimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing timelines
        CompetitionTimeline::truncate();

        // Get competitions by slug or name
        $edcCompetition = Competition::where('slug', 'like', '%edc%')
            ->orWhere('name', 'like', '%English Debate%')
            ->first();

        $kdbiCompetition = Competition::where('slug', 'like', '%kdbi%')
            ->orWhere('name', 'like', '%Debat Bahasa Indonesia%')
            ->first();

        $infografisCompetition = Competition::where('slug', 'like', '%infographic%')
            ->orWhere('slug', 'like', '%infografis%')
            ->orWhere('name', 'like', '%Infographic%')
            ->orWhere('name', 'like', '%Infografis%')
            ->first();

        $shortVideoCompetition = Competition::where('slug', 'like', '%short%')
            ->orWhere('slug', 'like', '%video%')
            ->orWhere('name', 'like', '%Short Video%')
            ->orWhere('name', 'like', '%Video%')
            ->first();

        // EDC Timeline
        if ($edcCompetition) {
            $this->createTimeline($edcCompetition->id, [
                ['month' => 'AUGUST', 'day' => '25-31', 'year' => '2025', 'title' => 'Registration EDC - Early Bird', 'order' => 1],
                ['month' => 'SEPTEMBER', 'day' => '1-13', 'year' => '2025', 'title' => 'Registration EDC - Phase 1', 'order' => 2],
                ['month' => 'SEPTEMBER', 'day' => '14-26', 'year' => '2025', 'title' => 'Registration EDC - Phase 2', 'order' => 3],
                ['month' => 'SEPTEMBER', 'day' => '27', 'year' => '2025', 'title' => 'Webinar and Participants Technical Meeting', 'order' => 4],
                ['month' => 'OCTOBER', 'day' => '13', 'year' => '2025', 'title' => 'Preliminary Round - Day 1', 'order' => 5],
                ['month' => 'OCTOBER', 'day' => '14', 'year' => '2025', 'title' => 'Preliminary Round - Day 2', 'order' => 6],
                ['month' => 'OCTOBER', 'day' => '15', 'year' => '2025', 'title' => 'Semifinal Debate', 'order' => 7],
                ['month' => 'OCTOBER', 'day' => '27', 'year' => '2025', 'title' => 'Final Round', 'order' => 8],
                ['month' => 'NOVEMBER', 'day' => '10', 'year' => '2025', 'title' => 'Award Ceremony UNAS FEST 2025', 'order' => 9],
            ]);
            $this->command->info('✅ EDC timeline created: 9 events');
        }

        // KDBI Timeline
        if ($kdbiCompetition) {
            $this->createTimeline($kdbiCompetition->id, [
                ['month' => 'AUGUST', 'day' => '25-31', 'year' => '2025', 'title' => 'Registration KDBI - Early Bird', 'order' => 1],
                ['month' => 'SEPTEMBER', 'day' => '1-13', 'year' => '2025', 'title' => 'Registration KDBI - Phase 1', 'order' => 2],
                ['month' => 'SEPTEMBER', 'day' => '14-26', 'year' => '2025', 'title' => 'Registration KDBI - Phase 2', 'order' => 3],
                ['month' => 'SEPTEMBER', 'day' => '27', 'year' => '2025', 'title' => 'Webinar and Participants Technical Meeting', 'order' => 4],
                ['month' => 'OCTOBER', 'day' => '13', 'year' => '2025', 'title' => 'Preliminary Round - Day 1', 'order' => 5],
                ['month' => 'OCTOBER', 'day' => '14', 'year' => '2025', 'title' => 'Preliminary Round - Day 2', 'order' => 6],
                ['month' => 'OCTOBER', 'day' => '15', 'year' => '2025', 'title' => 'Semifinal Debate', 'order' => 7],
                ['month' => 'OCTOBER', 'day' => '27', 'year' => '2025', 'title' => 'Final Round', 'order' => 8],
                ['month' => 'NOVEMBER', 'day' => '10', 'year' => '2025', 'title' => 'Award Ceremony UNAS FEST 2025', 'order' => 9],
            ]);
            $this->command->info('✅ KDBI timeline created: 9 events');
        }

        // Infografis Timeline
        if ($infografisCompetition) {
            $this->createTimeline($infografisCompetition->id, [
                ['month' => 'AUGUST', 'day' => '25-31', 'year' => '2025', 'title' => 'Registration Infografis - Early Bird', 'order' => 1],
                ['month' => 'SEPTEMBER', 'day' => '1-13', 'year' => '2025', 'title' => 'Registration Infografis - Phase 1', 'order' => 2],
                ['month' => 'SEPTEMBER', 'day' => '14-26', 'year' => '2025', 'title' => 'Registration Infografis - Phase 2', 'order' => 3],
                ['month' => 'SEPTEMBER', 'day' => '29', 'year' => '2025', 'title' => 'Webinar', 'order' => 4],
                ['month' => 'OCTOBER', 'day' => '2-4', 'year' => '2025', 'title' => 'Elimination of Participants by the Committee', 'order' => 5],
                ['month' => 'OCTOBER', 'day' => '7', 'year' => '2025', 'title' => 'Announcement', 'order' => 6],
                ['month' => 'OCTOBER', 'day' => '8-17', 'year' => '2025', 'title' => 'Collection of Works', 'order' => 7],
                ['month' => 'OCTOBER', 'day' => '18-21', 'year' => '2025', 'title' => 'Assessment', 'order' => 8],
                ['month' => 'OCTOBER', 'day' => '22', 'year' => '2025', 'title' => 'Announcement', 'order' => 9],
                ['month' => 'OCTOBER', 'day' => '23-25', 'year' => '2025', 'title' => 'PPT Collection', 'order' => 10],
                ['month' => 'OCTOBER', 'day' => '27', 'year' => '2025', 'title' => 'Final', 'order' => 11],
                ['month' => 'NOVEMBER', 'day' => '10', 'year' => '2025', 'title' => 'Award Ceremony UNAS FEST 2025', 'order' => 12],
            ]);
            $this->command->info('✅ Infografis timeline created: 12 events');
        }

        // Short Video Timeline
        if ($shortVideoCompetition) {
            $this->createTimeline($shortVideoCompetition->id, [
                ['month' => 'AUGUST', 'day' => '25-31', 'year' => '2025', 'title' => 'Registration Short Video - Early Bird', 'order' => 1],
                ['month' => 'SEPTEMBER', 'day' => '1-13', 'year' => '2025', 'title' => 'Registration Short Video - Phase 1', 'order' => 2],
                ['month' => 'SEPTEMBER', 'day' => '14-26', 'year' => '2025', 'title' => 'Registration Short Video - Phase 2', 'order' => 3],
                ['month' => 'SEPTEMBER', 'day' => '29', 'year' => '2025', 'title' => 'Webinar', 'order' => 4],
                ['month' => 'OCTOBER', 'day' => '2-4', 'year' => '2025', 'title' => 'Elimination of Participants by the Committee', 'order' => 5],
                ['month' => 'OCTOBER', 'day' => '7', 'year' => '2025', 'title' => 'Announcement', 'order' => 6],
                ['month' => 'OCTOBER', 'day' => '8-17', 'year' => '2025', 'title' => 'Collection of Works', 'order' => 7],
                ['month' => 'OCTOBER', 'day' => '18-21', 'year' => '2025', 'title' => 'Assessment', 'order' => 8],
                ['month' => 'OCTOBER', 'day' => '22', 'year' => '2025', 'title' => 'Announcement', 'order' => 9],
                ['month' => 'OCTOBER', 'day' => '23-25', 'year' => '2025', 'title' => 'PPT Collection', 'order' => 10],
                ['month' => 'OCTOBER', 'day' => '27', 'year' => '2025', 'title' => 'Final', 'order' => 11],
                ['month' => 'NOVEMBER', 'day' => '10', 'year' => '2025', 'title' => 'Award Ceremony UNAS FEST 2025', 'order' => 12],
            ]);
            $this->command->info('✅ Short Video timeline created: 12 events');
        }

        $totalEvents = CompetitionTimeline::count();
        $this->command->info("✅ Competition Timeline seeder completed: {$totalEvents} total events created");
    }

    /**
     * Create timeline events for a competition
     */
    private function createTimeline($competitionId, array $events): void
    {
        foreach ($events as $event) {
            CompetitionTimeline::create([
                'competition_id' => $competitionId,
                'month' => $event['month'],
                'day' => $event['day'],
                'year' => $event['year'],
                'title' => $event['title'],
                'order' => $event['order'],
                'is_active' => true,
            ]);
        }
    }
}

