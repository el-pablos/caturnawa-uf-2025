<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitorLogController extends Controller
{
    public function index(Request $request)
    {
        // Simple authentication check (you can modify this)
        $secretKey = $request->get('key');
        if ($secretKey !== 'unas-fest-2025-admin') {
            abort(403, 'Access denied. Invalid key.');
        }

        $date = $request->get('date', date('Y-m-d'));
        $limit = $request->get('limit', 50);
        $ipFilter = $request->get('ip');
        $countryFilter = $request->get('country');
        $browserFilter = $request->get('browser');

        $logFile = storage_path("logs/visitors/visitors_{$date}.txt");
        
        $logs = [];
        $stats = [
            'total_visits' => 0,
            'unique_ips' => 0,
            'countries' => [],
            'browsers' => [],
            'device_types' => [],
        ];

        if (file_exists($logFile)) {
            $content = file_get_contents($logFile);
            $entries = explode(str_repeat("=", 50), $content);
            
            // Remove empty entries
            $entries = array_filter($entries, function($entry) {
                return trim($entry) !== '';
            });

            $uniqueIps = [];
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

                // Collect stats from all entries (not just filtered)
                if (preg_match('/Real IP: (.+)/', $entry, $matches)) {
                    $ip = trim($matches[1]);
                    if ($ip !== 'unknown') {
                        $uniqueIps[$ip] = true;
                    }
                }

                if (preg_match('/Country: (.+)/', $entry, $matches)) {
                    $country = trim($matches[1]);
                    if ($country !== 'unknown') {
                        $stats['countries'][$country] = ($stats['countries'][$country] ?? 0) + 1;
                    }
                }

                if (preg_match('/Browser: (.+)/', $entry, $matches)) {
                    $browser = trim($matches[1]);
                    if ($browser !== 'unknown') {
                        $stats['browsers'][$browser] = ($stats['browsers'][$browser] ?? 0) + 1;
                    }
                }

                if (preg_match('/Device Type: (.+)/', $entry, $matches)) {
                    $deviceType = trim($matches[1]);
                    if ($deviceType !== 'unknown') {
                        $stats['device_types'][$deviceType] = ($stats['device_types'][$deviceType] ?? 0) + 1;
                    }
                }
            }

            $stats['total_visits'] = count($entries);
            $stats['unique_ips'] = count($uniqueIps);

            // Sort stats
            arsort($stats['countries']);
            arsort($stats['browsers']);
            arsort($stats['device_types']);

            // Get latest entries
            $filteredEntries = array_slice($filteredEntries, -$limit);
            $filteredEntries = array_reverse($filteredEntries);

            // Parse entries for display
            foreach ($filteredEntries as $entry) {
                $log = $this->parseLogEntry($entry);
                if ($log) {
                    $logs[] = $log;
                }
            }
        }

        // Get available dates
        $availableDates = [];
        $logDir = storage_path('logs/visitors');
        if (is_dir($logDir)) {
            $files = glob($logDir . '/visitors_*.txt');
            foreach ($files as $file) {
                $filename = basename($file);
                $fileDate = str_replace(['visitors_', '.txt'], '', $filename);
                $availableDates[] = $fileDate;
            }
            rsort($availableDates);
        }

        return view('admin.visitor-logs', compact('logs', 'stats', 'date', 'availableDates', 'limit', 'ipFilter', 'countryFilter', 'browserFilter'));
    }

    private function parseLogEntry($entry)
    {
        $log = [];

        // Extract basic info
        if (preg_match('/Timestamp: (.+)/', $entry, $matches)) {
            $log['timestamp'] = trim($matches[1]);
        }

        if (preg_match('/Real IP: (.+?) \((.+?)\)/', $entry, $matches)) {
            $log['real_ip'] = trim($matches[1]);
            $log['ip_type'] = trim($matches[2]);
        } elseif (preg_match('/Real IP: (.+)/', $entry, $matches)) {
            $log['real_ip'] = trim($matches[1]);
            $log['ip_type'] = 'unknown';
        }

        // Extract IP version info
        if (preg_match('/IP Version: (.+)/', $entry, $matches)) {
            $log['ip_version'] = trim($matches[1]);
        }

        if (preg_match('/Is Public IP: (.+)/', $entry, $matches)) {
            $log['is_public_ip'] = trim($matches[1]) === 'Yes';
        }

        // Extract all IPs
        $log['all_ips'] = [];
        if (preg_match('/--- ALL IP ADDRESSES DETECTED ---\n(.*?)\n--- HTTP HEADERS ---/s', $entry, $matches)) {
            $ipSection = trim($matches[1]);
            $ipLines = explode("\n", $ipSection);
            foreach ($ipLines as $line) {
                if (preg_match('/\s*(.+?): (.+?) \((.+?), (.+?)\)/', $line, $ipMatches)) {
                    $log['all_ips'][] = [
                        'source' => trim($ipMatches[1]),
                        'ip' => trim($ipMatches[2]),
                        'type' => trim($ipMatches[3]),
                        'status' => trim($ipMatches[4])
                    ];
                }
            }
        }

        if (preg_match('/URL: (.+)/', $entry, $matches)) {
            $log['url'] = trim($matches[1]);
        }

        if (preg_match('/Method: (.+)/', $entry, $matches)) {
            $log['method'] = trim($matches[1]);
        }

        if (preg_match('/Referer: (.+)/', $entry, $matches)) {
            $log['referer'] = trim($matches[1]);
        }

        if (preg_match('/User Agent: (.+)/', $entry, $matches)) {
            $log['user_agent'] = trim($matches[1]);
        }

        // Extract geo info
        if (preg_match('/Country: (.+)/', $entry, $matches)) {
            $log['country'] = trim($matches[1]);
        }

        if (preg_match('/City: (.+)/', $entry, $matches)) {
            $log['city'] = trim($matches[1]);
        }

        if (preg_match('/ISP: (.+)/', $entry, $matches)) {
            $log['isp'] = trim($matches[1]);
        }

        // Extract device info
        if (preg_match('/Browser: (.+)/', $entry, $matches)) {
            $log['browser'] = trim($matches[1]);
        }

        if (preg_match('/Browser Version: (.+)/', $entry, $matches)) {
            $log['browser_version'] = trim($matches[1]);
        }

        if (preg_match('/Platform: (.+)/', $entry, $matches)) {
            $log['platform'] = trim($matches[1]);
        }

        if (preg_match('/Device Type: (.+)/', $entry, $matches)) {
            $log['device_type'] = trim($matches[1]);
        }

        // Extract boolean flags
        $log['is_mobile'] = strpos($entry, 'Is Mobile: Yes') !== false;
        $log['is_tablet'] = strpos($entry, 'Is Tablet: Yes') !== false;
        $log['is_desktop'] = strpos($entry, 'Is Desktop: Yes') !== false;
        $log['is_bot'] = strpos($entry, 'Is Bot: Yes') !== false;

        return empty($log) ? null : $log;
    }

    public function export(Request $request)
    {
        // Simple authentication check
        $secretKey = $request->get('key');
        if ($secretKey !== 'unas-fest-2025-admin') {
            abort(403, 'Access denied. Invalid key.');
        }

        $date = $request->get('date', date('Y-m-d'));
        $format = $request->get('format', 'txt');
        
        $logFile = storage_path("logs/visitors/visitors_{$date}.txt");
        
        if (!file_exists($logFile)) {
            abort(404, 'Log file not found for the specified date.');
        }

        $filename = "visitor_logs_{$date}.{$format}";
        
        if ($format === 'csv') {
            return $this->exportCsv($logFile, $filename);
        }
        
        // Default: return raw txt file
        return response()->download($logFile, $filename);
    }

    private function exportCsv($logFile, $filename)
    {
        $content = file_get_contents($logFile);
        $entries = explode(str_repeat("=", 50), $content);
        
        // Remove empty entries
        $entries = array_filter($entries, function($entry) {
            return trim($entry) !== '';
        });

        $csvData = [];
        $csvData[] = [
            'Timestamp', 'Real IP', 'IP Type', 'IP Version', 'Is Public IP', 'All IPs Count',
            'URL', 'Method', 'Referer', 'User Agent', 'Country', 'City', 'ISP',
            'Browser', 'Browser Version', 'Platform', 'Device Type',
            'Is Mobile', 'Is Tablet', 'Is Desktop', 'Is Bot'
        ];

        foreach ($entries as $entry) {
            $log = $this->parseLogEntry($entry);
            if ($log) {
                $csvData[] = [
                    $log['timestamp'] ?? '',
                    $log['real_ip'] ?? '',
                    $log['ip_type'] ?? '',
                    $log['ip_version'] ?? '',
                    isset($log['is_public_ip']) ? ($log['is_public_ip'] ? 'Yes' : 'No') : '',
                    isset($log['all_ips']) ? count($log['all_ips']) : '0',
                    $log['url'] ?? '',
                    $log['method'] ?? '',
                    $log['referer'] ?? '',
                    $log['user_agent'] ?? '',
                    $log['country'] ?? '',
                    $log['city'] ?? '',
                    $log['isp'] ?? '',
                    $log['browser'] ?? '',
                    $log['browser_version'] ?? '',
                    $log['platform'] ?? '',
                    $log['device_type'] ?? '',
                    $log['is_mobile'] ? 'Yes' : 'No',
                    $log['is_tablet'] ? 'Yes' : 'No',
                    $log['is_desktop'] ? 'Yes' : 'No',
                    $log['is_bot'] ? 'Yes' : 'No',
                ];
            }
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
