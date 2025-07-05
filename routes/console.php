<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Custom Artisan Commands
|--------------------------------------------------------------------------
*/

// Command untuk expire registrations yang belum dibayar
Artisan::command('registrations:expire', function () {
    $expiredRegistrations = \App\Models\Registration::where('status', 'pending')
        ->where('created_at', '<', now()->subHours(24))
        ->get();
    
    foreach ($expiredRegistrations as $registration) {
        $registration->expire();
    }
    
    $this->info("Expired {$expiredRegistrations->count()} registrations.");
})->purpose('Expire pending registrations older than 24 hours');

// Command untuk generate QR codes yang belum ada
Artisan::command('qr:generate', function () {
    $registrations = \App\Models\Registration::where('status', 'confirmed')
        ->whereNull('qr_code')
        ->get();
    
    foreach ($registrations as $registration) {
        $registration->generateQRCode();
    }
    
    $this->info("Generated QR codes for {$registrations->count()} registrations.");
})->purpose('Generate QR codes for confirmed registrations');

// Command untuk cleanup old logs
Artisan::command('logs:cleanup', function () {
    $logPath = storage_path('logs');
    $files = glob($logPath . '/*.log');
    $deleted = 0;
    
    foreach ($files as $file) {
        if (filemtime($file) < strtotime('-30 days')) {
            unlink($file);
            $deleted++;
        }
    }
    
    $this->info("Deleted {$deleted} old log files.");
})->purpose('Delete log files older than 30 days');
