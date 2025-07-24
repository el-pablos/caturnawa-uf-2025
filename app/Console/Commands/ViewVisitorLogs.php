<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class ViewVisitorLogs extends Command
{
    protected $signature = 'visitors:view {date?} {--tail=20} {--ip=} {--country=} {--browser=}';
    protected $description = 'View visitor logs with filtering options';

    public function handle()
    {
        $date = $this->argument('date') ?? date('Y-m-d');
        $tail = $this->option('tail');
        $ipFilter = $this->option('ip');
        $countryFilter = $this->option('country');
        $browserFilter = $this->option('browser');

        $logFile = storage_path("logs/visitors/visitors_{$date}.txt");

        if (!file_exists($logFile)) {
            $this->error("No visitor log found for date: {$date}");
            $this->info("Available log files:");
            
            $logDir = storage_path('logs/visitors');
            if (is_dir($logDir)) {
                $files = glob($logDir . '/visitors_*.txt');
                foreach ($files as $file) {
                    $filename = basename($file);
                    $fileDate = str_replace(['visitors_', '.txt'], '', $filename);
                    $this->line("  - {$fileDate}");
                }
            }
            return;
        }

        $content = file_get_contents($logFile);
        $entries = explode(str_repeat("=", 50), $content);
        
        // Remove empty entries
        $entries = array_filter($entries, function($entry) {
            return trim($entry) !== '';
        });

        $filteredEntries = [];
        
        foreach ($entries as $entry) {
            $include = true;
            
            // Apply filters
            if ($ipFilter && stripos($entry, "Real IP: {$ipFilter}") === false) {
                $include = false;
            }
            
            if ($countryFilter && stripos($entry, "Country: {$countryFilter}") === false) {
                $include = false;
            }
            
            if ($browserFilter && stripos($entry, "Browser: {$browserFilter}") === false) {
                $include = false;
            }
            
            if ($include) {
                $filteredEntries[] = $entry;
            }
        }

        if (empty($filteredEntries)) {
            $this->warn("No entries found matching the specified filters.");
            return;
        }

        // Get last N entries if tail option is used
        if ($tail > 0) {
            $filteredEntries = array_slice($filteredEntries, -$tail);
        }

        $this->info("Visitor Log for {$date}");
        $this->info("Total entries: " . count($filteredEntries));
        $this->line(str_repeat("=", 80));

        foreach ($filteredEntries as $entry) {
            $this->line($entry);
            $this->line(str_repeat("=", 50));
        }
    }
}
