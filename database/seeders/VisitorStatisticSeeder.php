<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VisitorStatistic;
use Carbon\Carbon;

class VisitorStatisticSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create some sample visitor data for testing
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        
        // Today's visitors
        for ($i = 0; $i < 25; $i++) {
            VisitorStatistic::create([
                'ip_address' => '192.168.1.' . ($i + 100),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'page_url' => 'https://unasfest.com/',
                'referrer' => 'https://google.com',
                'country' => 'Indonesia',
                'city' => 'Jakarta',
                'is_unique_today' => true,
                'visited_at' => $today->copy()->addHours(rand(8, 22))->addMinutes(rand(0, 59)),
            ]);
        }
        
        // This week's visitors (not today)
        for ($day = 1; $day <= 6; $day++) {
            $date = $thisWeek->copy()->addDays($day - 1);
            if ($date->isToday()) continue;
            
            for ($i = 0; $i < rand(15, 40); $i++) {
                VisitorStatistic::create([
                    'ip_address' => '10.0.0.' . ($i + 50),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'page_url' => 'https://unasfest.com/competitions',
                    'referrer' => 'https://facebook.com',
                    'country' => 'Indonesia',
                    'city' => 'Bandung',
                    'is_unique_today' => true,
                    'visited_at' => $date->copy()->addHours(rand(8, 22))->addMinutes(rand(0, 59)),
                ]);
            }
        }
        
        // Historical visitors (for total count)
        for ($day = 1; $day <= 30; $day++) {
            $date = Carbon::now()->subDays($day);
            
            for ($i = 0; $i < rand(10, 50); $i++) {
                VisitorStatistic::create([
                    'ip_address' => '172.16.0.' . ($i + 10),
                    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                    'page_url' => 'https://unasfest.com/about',
                    'referrer' => 'https://instagram.com',
                    'country' => 'Indonesia',
                    'city' => 'Surabaya',
                    'is_unique_today' => true,
                    'visited_at' => $date->copy()->addHours(rand(8, 22))->addMinutes(rand(0, 59)),
                ]);
            }
        }
    }
}
