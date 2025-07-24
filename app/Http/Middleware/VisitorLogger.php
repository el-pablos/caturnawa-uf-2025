<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VisitorLogger
{
    public function handle(Request $request, Closure $next)
    {
        // Get real IP address
        $realIp = $this->getRealIpAddress($request);
        
        // Get detailed visitor information
        $visitorData = [
            'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            'real_ip' => $realIp,
            'forwarded_ip' => $request->header('X-Forwarded-For'),
            'remote_addr' => $request->server('REMOTE_ADDR'),
            'user_agent' => $request->header('User-Agent'),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'referer' => $request->header('Referer'),
            'accept_language' => $request->header('Accept-Language'),
            'accept_encoding' => $request->header('Accept-Encoding'),
            'connection' => $request->header('Connection'),
            'host' => $request->header('Host'),
            'origin' => $request->header('Origin'),
            'sec_fetch_site' => $request->header('Sec-Fetch-Site'),
            'sec_fetch_mode' => $request->header('Sec-Fetch-Mode'),
            'sec_fetch_dest' => $request->header('Sec-Fetch-Dest'),
            'cache_control' => $request->header('Cache-Control'),
            'upgrade_insecure_requests' => $request->header('Upgrade-Insecure-Requests'),
            'session_id' => $request->session()->getId(),
            'csrf_token' => $request->session()->token(),
        ];

        // Get geolocation data
        $geoData = $this->getGeolocationData($realIp);
        $visitorData = array_merge($visitorData, $geoData);

        // Get device and browser information
        $deviceInfo = $this->parseUserAgent($request->header('User-Agent'));
        $visitorData = array_merge($visitorData, $deviceInfo);

        // Get server information
        $serverInfo = [
            'server_name' => $request->server('SERVER_NAME'),
            'server_port' => $request->server('SERVER_PORT'),
            'server_protocol' => $request->server('SERVER_PROTOCOL'),
            'request_scheme' => $request->server('REQUEST_SCHEME'),
            'https' => $request->server('HTTPS'),
            'query_string' => $request->server('QUERY_STRING'),
            'request_uri' => $request->server('REQUEST_URI'),
            'script_name' => $request->server('SCRIPT_NAME'),
        ];
        $visitorData = array_merge($visitorData, $serverInfo);

        // Log to custom file
        $this->logVisitorData($visitorData);

        return $next($request);
    }

    private function getRealIpAddress($request)
    {
        // Check for various headers that might contain the real IP
        $ipHeaders = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',            // Proxy
            'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
            'HTTP_X_FORWARDED',          // Proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
            'HTTP_X_REAL_IP',            // Nginx proxy
            'REMOTE_ADDR'                // Standard
        ];

        foreach ($ipHeaders as $header) {
            $ip = $request->server($header);
            if (!empty($ip) && $ip !== 'unknown') {
                // Handle comma-separated IPs (X-Forwarded-For can contain multiple IPs)
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }
                
                // Validate IP address
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $request->server('REMOTE_ADDR', 'unknown');
    }

    private function getGeolocationData($ip)
    {
        if ($ip === 'unknown' || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return [
                'country' => 'unknown',
                'country_code' => 'unknown',
                'region' => 'unknown',
                'city' => 'unknown',
                'latitude' => 'unknown',
                'longitude' => 'unknown',
                'timezone' => 'unknown',
                'isp' => 'unknown',
                'organization' => 'unknown',
                'as_number' => 'unknown',
            ];
        }

        try {
            // Using ip-api.com (free service, no API key required)
            $geoUrl = "http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,region,regionName,city,lat,lon,timezone,isp,org,as,query";
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'user_agent' => 'UNAS-Fest-2025-Logger/1.0'
                ]
            ]);
            
            $response = @file_get_contents($geoUrl, false, $context);
            
            if ($response !== false) {
                $data = json_decode($response, true);
                
                if ($data && $data['status'] === 'success') {
                    return [
                        'country' => $data['country'] ?? 'unknown',
                        'country_code' => $data['countryCode'] ?? 'unknown',
                        'region' => $data['regionName'] ?? 'unknown',
                        'city' => $data['city'] ?? 'unknown',
                        'latitude' => $data['lat'] ?? 'unknown',
                        'longitude' => $data['lon'] ?? 'unknown',
                        'timezone' => $data['timezone'] ?? 'unknown',
                        'isp' => $data['isp'] ?? 'unknown',
                        'organization' => $data['org'] ?? 'unknown',
                        'as_number' => $data['as'] ?? 'unknown',
                    ];
                }
            }
        } catch (\Exception $e) {
            // Silently fail and return unknown values
        }

        return [
            'country' => 'unknown',
            'country_code' => 'unknown',
            'region' => 'unknown',
            'city' => 'unknown',
            'latitude' => 'unknown',
            'longitude' => 'unknown',
            'timezone' => 'unknown',
            'isp' => 'unknown',
            'organization' => 'unknown',
            'as_number' => 'unknown',
        ];
    }

    private function parseUserAgent($userAgent)
    {
        if (empty($userAgent)) {
            return [
                'browser' => 'unknown',
                'browser_version' => 'unknown',
                'platform' => 'unknown',
                'device_type' => 'unknown',
                'is_mobile' => false,
                'is_tablet' => false,
                'is_desktop' => false,
                'is_bot' => false,
            ];
        }

        $browser = 'unknown';
        $browserVersion = 'unknown';
        $platform = 'unknown';
        $deviceType = 'unknown';
        $isMobile = false;
        $isTablet = false;
        $isDesktop = false;
        $isBot = false;

        // Detect bots
        $botPatterns = [
            'bot', 'crawler', 'spider', 'scraper', 'facebook', 'twitter', 'google', 'bing', 'yahoo', 'baidu'
        ];
        
        foreach ($botPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                $isBot = true;
                break;
            }
        }

        // Detect browser
        if (preg_match('/Chrome\/([0-9.]+)/', $userAgent, $matches)) {
            $browser = 'Chrome';
            $browserVersion = $matches[1];
        } elseif (preg_match('/Firefox\/([0-9.]+)/', $userAgent, $matches)) {
            $browser = 'Firefox';
            $browserVersion = $matches[1];
        } elseif (preg_match('/Safari\/([0-9.]+)/', $userAgent, $matches) && !preg_match('/Chrome/', $userAgent)) {
            $browser = 'Safari';
            $browserVersion = $matches[1];
        } elseif (preg_match('/Edge\/([0-9.]+)/', $userAgent, $matches)) {
            $browser = 'Edge';
            $browserVersion = $matches[1];
        } elseif (preg_match('/Opera\/([0-9.]+)/', $userAgent, $matches)) {
            $browser = 'Opera';
            $browserVersion = $matches[1];
        }

        // Detect platform
        if (preg_match('/Windows NT ([0-9.]+)/', $userAgent, $matches)) {
            $platform = 'Windows ' . $matches[1];
            $isDesktop = true;
        } elseif (preg_match('/Mac OS X ([0-9_]+)/', $userAgent, $matches)) {
            $platform = 'macOS ' . str_replace('_', '.', $matches[1]);
            $isDesktop = true;
        } elseif (preg_match('/Linux/', $userAgent)) {
            $platform = 'Linux';
            $isDesktop = true;
        } elseif (preg_match('/Android ([0-9.]+)/', $userAgent, $matches)) {
            $platform = 'Android ' . $matches[1];
            $isMobile = true;
        } elseif (preg_match('/iPhone OS ([0-9_]+)/', $userAgent, $matches)) {
            $platform = 'iOS ' . str_replace('_', '.', $matches[1]);
            $isMobile = true;
        } elseif (preg_match('/iPad/', $userAgent)) {
            $platform = 'iPadOS';
            $isTablet = true;
        }

        // Detect device type
        if ($isMobile) {
            $deviceType = 'mobile';
        } elseif ($isTablet) {
            $deviceType = 'tablet';
        } elseif ($isDesktop) {
            $deviceType = 'desktop';
        } elseif ($isBot) {
            $deviceType = 'bot';
        }

        return [
            'browser' => $browser,
            'browser_version' => $browserVersion,
            'platform' => $platform,
            'device_type' => $deviceType,
            'is_mobile' => $isMobile,
            'is_tablet' => $isTablet,
            'is_desktop' => $isDesktop,
            'is_bot' => $isBot,
        ];
    }

    private function logVisitorData($data)
    {
        // Create logs directory if it doesn't exist
        $logDir = storage_path('logs/visitors');
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }

        // Create filename with date
        $filename = $logDir . '/visitors_' . date('Y-m-d') . '.txt';

        // Format data for logging
        $logEntry = $this->formatLogEntry($data);

        // Append to file
        file_put_contents($filename, $logEntry . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    private function formatLogEntry($data)
    {
        $formatted = "=== VISITOR LOG ENTRY ===\n";
        $formatted .= "Timestamp: {$data['timestamp']}\n";
        $formatted .= "Real IP: {$data['real_ip']}\n";
        $formatted .= "Forwarded IP: {$data['forwarded_ip']}\n";
        $formatted .= "Remote Address: {$data['remote_addr']}\n";
        $formatted .= "URL: {$data['url']}\n";
        $formatted .= "Method: {$data['method']}\n";
        $formatted .= "Referer: {$data['referer']}\n";
        $formatted .= "User Agent: {$data['user_agent']}\n";
        $formatted .= "Accept Language: {$data['accept_language']}\n";
        $formatted .= "Accept Encoding: {$data['accept_encoding']}\n";
        $formatted .= "Connection: {$data['connection']}\n";
        $formatted .= "Host: {$data['host']}\n";
        $formatted .= "Origin: {$data['origin']}\n";
        $formatted .= "Session ID: {$data['session_id']}\n";
        $formatted .= "CSRF Token: {$data['csrf_token']}\n";
        
        $formatted .= "\n--- GEOLOCATION DATA ---\n";
        $formatted .= "Country: {$data['country']}\n";
        $formatted .= "Country Code: {$data['country_code']}\n";
        $formatted .= "Region: {$data['region']}\n";
        $formatted .= "City: {$data['city']}\n";
        $formatted .= "Latitude: {$data['latitude']}\n";
        $formatted .= "Longitude: {$data['longitude']}\n";
        $formatted .= "Timezone: {$data['timezone']}\n";
        $formatted .= "ISP: {$data['isp']}\n";
        $formatted .= "Organization: {$data['organization']}\n";
        $formatted .= "AS Number: {$data['as_number']}\n";
        
        $formatted .= "\n--- DEVICE & BROWSER INFO ---\n";
        $formatted .= "Browser: {$data['browser']}\n";
        $formatted .= "Browser Version: {$data['browser_version']}\n";
        $formatted .= "Platform: {$data['platform']}\n";
        $formatted .= "Device Type: {$data['device_type']}\n";
        $formatted .= "Is Mobile: " . ($data['is_mobile'] ? 'Yes' : 'No') . "\n";
        $formatted .= "Is Tablet: " . ($data['is_tablet'] ? 'Yes' : 'No') . "\n";
        $formatted .= "Is Desktop: " . ($data['is_desktop'] ? 'Yes' : 'No') . "\n";
        $formatted .= "Is Bot: " . ($data['is_bot'] ? 'Yes' : 'No') . "\n";
        
        $formatted .= "\n--- SERVER INFO ---\n";
        $formatted .= "Server Name: {$data['server_name']}\n";
        $formatted .= "Server Port: {$data['server_port']}\n";
        $formatted .= "Server Protocol: {$data['server_protocol']}\n";
        $formatted .= "Request Scheme: {$data['request_scheme']}\n";
        $formatted .= "HTTPS: {$data['https']}\n";
        $formatted .= "Query String: {$data['query_string']}\n";
        $formatted .= "Request URI: {$data['request_uri']}\n";
        $formatted .= "Script Name: {$data['script_name']}\n";
        
        $formatted .= "\n" . str_repeat("=", 50) . "\n";
        
        return $formatted;
    }
}
