<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Expire old pending registrations every hour
        $schedule->command('registrations:expire')->hourly();
        
        // Generate missing QR codes daily
        $schedule->command('qr:generate')->daily();
        
        // Clean up old logs weekly
        $schedule->command('logs:cleanup')->weekly();
        
        // Send competition reminders
        // $schedule->command('competitions:remind')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
