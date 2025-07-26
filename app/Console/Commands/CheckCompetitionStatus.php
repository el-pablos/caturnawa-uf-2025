<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Competition;
use Carbon\Carbon;

class CheckCompetitionStatus extends Command
{
    protected $signature = 'competition:status {--detailed : Show detailed information}';
    protected $description = 'Check competition status and registration availability';

    public function handle()
    {
        $this->info('🏆 UNAS Fest 2025 - Competition Status Check');
        $this->info('Current Date: ' . now()->format('Y-m-d H:i:s'));
        $this->line(str_repeat('=', 80));

        $competitions = Competition::orderBy('registration_start')->get();
        
        if ($competitions->isEmpty()) {
            $this->warn('No competitions found in database.');
            return;
        }

        $this->table(
            ['ID', 'Competition', 'Active', 'Reg. Start', 'Reg. End', 'Status', 'Public', 'Peserta'],
            $competitions->map(function ($competition) {
                return [
                    $competition->id,
                    $this->truncate($competition->name, 25),
                    $competition->is_active ? '✅ Yes' : '❌ No',
                    $competition->registration_start->format('M d, Y'),
                    $competition->registration_end->format('M d, Y'),
                    $this->getRegistrationStatus($competition),
                    $this->getPublicVisibility($competition),
                    $this->getPesertaVisibility($competition)
                ];
            })->toArray()
        );

        if ($this->option('detailed')) {
            $this->showDetailedInfo($competitions);
        }

        $this->showSummary($competitions);
    }

    private function getRegistrationStatus($competition)
    {
        if (!$competition->is_active) {
            return '🔴 Inactive';
        }

        $now = now();
        
        if ($now < $competition->registration_start) {
            return '🟡 Not Started';
        } elseif ($now > $competition->registration_end) {
            return '🔴 Closed';
        } else {
            return '🟢 Open';
        }
    }

    private function getPublicVisibility($competition)
    {
        // Public shows all active competitions regardless of registration dates
        return $competition->is_active ? '👁️ Visible' : '🚫 Hidden';
    }

    private function getPesertaVisibility($competition)
    {
        // Peserta only sees competitions with open registration
        if (!$competition->is_active) {
            return '🚫 Hidden';
        }

        return $competition->isRegistrationOpen() ? '👁️ Visible' : '🚫 Hidden';
    }

    private function showDetailedInfo($competitions)
    {
        $this->line('');
        $this->info('📋 Detailed Competition Information:');
        $this->line(str_repeat('-', 80));

        foreach ($competitions as $competition) {
            $this->line('');
            $this->info("🏆 {$competition->name}");
            $this->line("   ID: {$competition->id}");
            $this->line("   Slug: {$competition->slug}");
            $this->line("   Category: " . ucfirst($competition->category));
            $this->line("   Price: Rp " . number_format($competition->price, 0, ',', '.'));
            $this->line("   Early Bird: Rp " . number_format($competition->early_bird_price, 0, ',', '.'));
            $this->line("   Early Bird Deadline: " . $competition->early_bird_deadline->format('Y-m-d H:i:s'));
            $this->line("   Registration Period: " . $competition->registration_start->format('Y-m-d H:i:s') . ' → ' . $competition->registration_end->format('Y-m-d H:i:s'));
            $this->line("   Competition Period: " . $competition->competition_start->format('Y-m-d H:i:s') . ' → ' . $competition->competition_end->format('Y-m-d H:i:s'));
            
            if ($competition->submission_start && $competition->submission_end) {
                $this->line("   Submission Period: " . $competition->submission_start->format('Y-m-d H:i:s') . ' → ' . $competition->submission_end->format('Y-m-d H:i:s'));
            }
            
            $this->line("   Max Participants: {$competition->max_participants}");
            $this->line("   Team Competition: " . ($competition->is_team_competition ? 'Yes' : 'No'));
            $this->line("   Allow Individual: " . ($competition->allow_individual ? 'Yes' : 'No'));
            $this->line("   Team Size: {$competition->min_team_members} - {$competition->max_team_members} members");
            
            // Status indicators
            $statusColor = $competition->isRegistrationOpen() ? 'info' : 'comment';
            $this->line("   Registration Status: " . $this->getRegistrationStatus($competition));
            $this->line("   Days until registration ends: " . now()->diffInDays($competition->registration_end, false));
            
            $this->line(str_repeat('-', 40));
        }
    }

    private function showSummary($competitions)
    {
        $this->line('');
        $this->info('📊 Summary:');
        
        $total = $competitions->count();
        $active = $competitions->where('is_active', true)->count();
        $openRegistration = $competitions->filter(function ($c) {
            return $c->isRegistrationOpen();
        })->count();
        $closedRegistration = $competitions->filter(function ($c) {
            return $c->is_active && !$c->isRegistrationOpen() && now() > $c->registration_end;
        })->count();
        $notStarted = $competitions->filter(function ($c) {
            return $c->is_active && now() < $c->registration_start;
        })->count();

        $this->table(
            ['Metric', 'Count', 'Percentage'],
            [
                ['Total Competitions', $total, '100%'],
                ['Active Competitions', $active, round(($active / $total) * 100, 1) . '%'],
                ['Open Registration', $openRegistration, round(($openRegistration / $total) * 100, 1) . '%'],
                ['Registration Not Started', $notStarted, round(($notStarted / $total) * 100, 1) . '%'],
                ['Registration Closed', $closedRegistration, round(($closedRegistration / $total) * 100, 1) . '%'],
            ]
        );

        $this->line('');
        $this->info('🎯 Visibility Summary:');
        $this->line("   Public Page: Shows {$active} competitions (all active)");
        $this->line("   Peserta Dashboard: Shows {$openRegistration} competitions (open registration only)");
        $this->line("   Admin Dashboard: Shows {$total} competitions (all competitions)");

        if ($openRegistration > 0) {
            $this->line('');
            $this->info('🟢 Competitions with Open Registration:');
            $competitions->filter(function ($c) {
                return $c->isRegistrationOpen();
            })->each(function ($c) {
                $daysLeft = now()->diffInDays($c->registration_end, false);
                $this->line("   • {$c->name} (ends in {$daysLeft} days)");
            });
        }

        if ($notStarted > 0) {
            $this->line('');
            $this->info('🟡 Competitions Not Started Yet:');
            $competitions->filter(function ($c) {
                return $c->is_active && now() < $c->registration_start;
            })->each(function ($c) {
                $daysUntil = now()->diffInDays($c->registration_start, false);
                $this->line("   • {$c->name} (starts in {$daysUntil} days)");
            });
        }
    }

    private function truncate($string, $length)
    {
        return strlen($string) > $length ? substr($string, 0, $length - 3) . '...' : $string;
    }
}
