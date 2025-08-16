<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class VisitorStats extends Command
{
    protected $signature = 'visitors:stats {date?}';
    protected $description = 'Show visitor statistics for a specific date';

    public function handle()
    {
        $date = $this->argument('date') ?? date('Y-m-d');
        $logFile = storage_path("logs/visitors/visitors_{$date}.txt");

        if (!file_exists($logFile)) {
            $this->error("No visitor log found for date: {$date}");
            return;
        }

        $content = file_get_contents($logFile);
        $entries = explode(str_repeat("=", 50), $content);
        
        // Remove empty entries
        $entries = array_filter($entries, function($entry) {
            return trim($entry) !== '';
        });

        $stats = [
            'total_visits' => count($entries),
            'unique_ips' => [],
            'countries' => [],
            'browsers' => [],
            'platforms' => [],
            'device_types' => [],
            'pages' => [],
            'referers' => [],
            'isps' => [],
            'bots' => 0,
            'mobile' => 0,
            'desktop' => 0,
            'tablet' => 0,
        ];

        foreach ($entries as $entry) {
            // Extract IP
            if (preg_match('/Real IP: (.+)/', $entry, $matches)) {
                $ip = trim($matches[1]);
                if ($ip !== 'unknown') {
                    $stats['unique_ips'][$ip] = ($stats['unique_ips'][$ip] ?? 0) + 1;
                }
            }

            // Extract Country
            if (preg_match('/Country: (.+)/', $entry, $matches)) {
                $country = trim($matches[1]);
                if ($country !== 'unknown') {
                    $stats['countries'][$country] = ($stats['countries'][$country] ?? 0) + 1;
                }
            }

            // Extract Browser
            if (preg_match('/Browser: (.+)/', $entry, $matches)) {
                $browser = trim($matches[1]);
                if ($browser !== 'unknown') {
                    $stats['browsers'][$browser] = ($stats['browsers'][$browser] ?? 0) + 1;
                }
            }

            // Extract Platform
            if (preg_match('/Platform: (.+)/', $entry, $matches)) {
                $platform = trim($matches[1]);
                if ($platform !== 'unknown') {
                    $stats['platforms'][$platform] = ($stats['platforms'][$platform] ?? 0) + 1;
                }
            }

            // Extract Device Type
            if (preg_match('/Device Type: (.+)/', $entry, $matches)) {
                $deviceType = trim($matches[1]);
                if ($deviceType !== 'unknown') {
                    $stats['device_types'][$deviceType] = ($stats['device_types'][$deviceType] ?? 0) + 1;
                }
            }

            // Extract URL
            if (preg_match('/URL: (.+)/', $entry, $matches)) {
                $url = trim($matches[1]);
                $path = parse_url($url, PHP_URL_PATH) ?? '/';
                $stats['pages'][$path] = ($stats['pages'][$path] ?? 0) + 1;
            }

            // Extract Referer
            if (preg_match('/Referer: (.+)/', $entry, $matches)) {
                $referer = trim($matches[1]);
                if ($referer && $referer !== 'unknown') {
                    $domain = parse_url($referer, PHP_URL_HOST) ?? $referer;
                    $stats['referers'][$domain] = ($stats['referers'][$domain] ?? 0) + 1;
                }
            }

            // Extract ISP
            if (preg_match('/ISP: (.+)/', $entry, $matches)) {
                $isp = trim($matches[1]);
                if ($isp !== 'unknown') {
                    $stats['isps'][$isp] = ($stats['isps'][$isp] ?? 0) + 1;
                }
            }

            // Count device types
            if (strpos($entry, 'Is Bot: Yes') !== false) {
                $stats['bots']++;
            }
            if (strpos($entry, 'Is Mobile: Yes') !== false) {
                $stats['mobile']++;
            }
            if (strpos($entry, 'Is Desktop: Yes') !== false) {
                $stats['desktop']++;
            }
            if (strpos($entry, 'Is Tablet: Yes') !== false) {
                $stats['tablet']++;
            }
        }

        // Sort arrays by count (descending)
        arsort($stats['unique_ips']);
        arsort($stats['countries']);
        arsort($stats['browsers']);
        arsort($stats['platforms']);
        arsort($stats['device_types']);
        arsort($stats['pages']);
        arsort($stats['referers']);
        arsort($stats['isps']);

        // Display statistics
        $this->info("Visitor Statistics for {$date}");
        $this->line(str_repeat("=", 80));

        $this->info("📊 OVERVIEW");
        $this->line("Total Visits: " . $stats['total_visits']);
        $this->line("Unique IPs: " . count($stats['unique_ips']));
        $this->line("Bots: " . $stats['bots']);
        $this->line("Mobile: " . $stats['mobile']);
        $this->line("Desktop: " . $stats['desktop']);
        $this->line("Tablet: " . $stats['tablet']);
        $this->line("");

        $this->info("🌍 TOP COUNTRIES");
        $this->displayTop($stats['countries'], 10);

        $this->info("🌐 TOP BROWSERS");
        $this->displayTop($stats['browsers'], 10);

        $this->info("💻 TOP PLATFORMS");
        $this->displayTop($stats['platforms'], 10);

        $this->info("📱 DEVICE TYPES");
        $this->displayTop($stats['device_types'], 10);

        $this->info("📄 TOP PAGES");
        $this->displayTop($stats['pages'], 15);

        $this->info("🔗 TOP REFERERS");
        $this->displayTop($stats['referers'], 10);

        $this->info("🌐 TOP ISPs");
        $this->displayTop($stats['isps'], 10);

        $this->info("🔍 TOP IPs");
        $this->displayTop($stats['unique_ips'], 15);
    }

    private function displayTop($array, $limit = 10)
    {
        $count = 0;
        foreach ($array as $key => $value) {
            if ($count >= $limit) break;
            $this->line("  {$key}: {$value}");
            $count++;
        }
        $this->line("");
    }
}
