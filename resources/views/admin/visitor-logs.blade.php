<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Logs - UNAS Fest 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 30px;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .log-entry {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .log-entry:hover {
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .badge-custom {
            font-size: 0.8em;
            padding: 5px 10px;
        }
        
        .filter-section {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .btn-custom {
            background: linear-gradient(45deg, #ff6b6b, #feca57);
            border: none;
            color: white;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
            color: white;
        }
        
        .auto-refresh {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .country-flag {
            width: 20px;
            height: 15px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="auto-refresh">
        <button class="btn btn-success btn-sm" onclick="toggleAutoRefresh()">
            <i class="bi bi-arrow-clockwise"></i> Auto Refresh: <span id="refresh-status">OFF</span>
        </button>
    </div>

    <div class="container-fluid">
        <div class="main-container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center mb-4">
                        <i class="bi bi-graph-up text-primary"></i>
                        Visitor Logs - UNAS Fest 2025
                    </h1>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row">
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <h3>{{ $stats['total_visits'] }}</h3>
                        <p class="mb-0">Total Visits</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <h3>{{ $stats['unique_ips'] }}</h3>
                        <p class="mb-0">Unique IPs</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <h3>{{ count($stats['countries']) }}</h3>
                        <p class="mb-0">Countries</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <h3>{{ count($stats['browsers']) }}</h3>
                        <p class="mb-0">Browsers</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filter-section">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="key" value="{{ request('key') }}">
                    
                    <div class="col-md-2">
                        <label class="form-label">Date</label>
                        <select name="date" class="form-select">
                            @foreach($availableDates as $availableDate)
                                <option value="{{ $availableDate }}" {{ $date == $availableDate ? 'selected' : '' }}>
                                    {{ $availableDate }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Limit</label>
                        <select name="limit" class="form-select">
                            <option value="25" {{ $limit == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $limit == 100 ? 'selected' : '' }}>100</option>
                            <option value="200" {{ $limit == 200 ? 'selected' : '' }}>200</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">IP Filter</label>
                        <input type="text" name="ip" class="form-control" value="{{ $ipFilter }}" placeholder="e.g., 192.168.1.1">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Country</label>
                        <select name="country" class="form-select">
                            <option value="">All Countries</option>
                            @foreach(array_keys($stats['countries']) as $country)
                                <option value="{{ $country }}" {{ $countryFilter == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Browser</label>
                        <select name="browser" class="form-select">
                            <option value="">All Browsers</option>
                            @foreach(array_keys($stats['browsers']) as $browser)
                                <option value="{{ $browser }}" {{ $browserFilter == $browser ? 'selected' : '' }}>
                                    {{ $browser }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-custom me-2">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="?key={{ request('key') }}&date={{ $date }}&format=csv" class="btn btn-outline-success">
                            <i class="bi bi-download"></i> CSV
                        </a>
                    </div>
                </form>
            </div>

            <!-- Top Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <h5><i class="bi bi-globe"></i> Top Countries</h5>
                    <ul class="list-group">
                        @foreach(array_slice($stats['countries'], 0, 5, true) as $country => $count)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $country }}
                                <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <h5><i class="bi bi-browser-chrome"></i> Top Browsers</h5>
                    <ul class="list-group">
                        @foreach(array_slice($stats['browsers'], 0, 5, true) as $browser => $count)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $browser }}
                                <span class="badge bg-success rounded-pill">{{ $count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <h5><i class="bi bi-phone"></i> Device Types</h5>
                    <ul class="list-group">
                        @foreach(array_slice($stats['device_types'], 0, 5, true) as $device => $count)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ ucfirst($device) }}
                                <span class="badge bg-warning rounded-pill">{{ $count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Visitor Logs -->
            <div class="row">
                <div class="col-12">
                    <h4><i class="bi bi-list-ul"></i> Recent Visitors ({{ count($logs) }} entries)</h4>
                    
                    @forelse($logs as $log)
                        <div class="log-entry">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-2">
                                        <strong class="text-primary me-3">{{ $log['real_ip'] ?? 'Unknown' }}</strong>
                                        
                                        @if(isset($log['country']) && $log['country'] !== 'unknown')
                                            <span class="badge bg-info badge-custom me-2">
                                                <i class="bi bi-geo-alt"></i> {{ $log['country'] }}
                                            </span>
                                        @endif
                                        
                                        @if(isset($log['device_type']))
                                            @if($log['device_type'] === 'mobile')
                                                <span class="badge bg-success badge-custom me-2">
                                                    <i class="bi bi-phone"></i> Mobile
                                                </span>
                                            @elseif($log['device_type'] === 'desktop')
                                                <span class="badge bg-primary badge-custom me-2">
                                                    <i class="bi bi-display"></i> Desktop
                                                </span>
                                            @elseif($log['device_type'] === 'tablet')
                                                <span class="badge bg-warning badge-custom me-2">
                                                    <i class="bi bi-tablet"></i> Tablet
                                                </span>
                                            @elseif($log['device_type'] === 'bot')
                                                <span class="badge bg-danger badge-custom me-2">
                                                    <i class="bi bi-robot"></i> Bot
                                                </span>
                                            @endif
                                        @endif
                                        
                                        @if(isset($log['browser']) && $log['browser'] !== 'unknown')
                                            <span class="badge bg-secondary badge-custom me-2">
                                                {{ $log['browser'] }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong>URL:</strong> 
                                        <a href="{{ $log['url'] ?? '#' }}" target="_blank" class="text-decoration-none">
                                            {{ $log['url'] ?? 'Unknown' }}
                                        </a>
                                    </div>
                                    
                                    @if(isset($log['referer']) && $log['referer'] && $log['referer'] !== 'unknown')
                                        <div class="mb-2">
                                            <strong>Referer:</strong> 
                                            <small class="text-muted">{{ Str::limit($log['referer'], 80) }}</small>
                                        </div>
                                    @endif
                                    
                                    @if(isset($log['user_agent']))
                                        <div class="mb-2">
                                            <strong>User Agent:</strong> 
                                            <small class="text-muted">{{ Str::limit($log['user_agent'], 100) }}</small>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="col-md-4 text-end">
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $log['timestamp'] ?? 'Unknown' }}
                                        </small>
                                    </div>
                                    
                                    @if(isset($log['city']) && $log['city'] !== 'unknown')
                                        <div class="mb-1">
                                            <small><i class="bi bi-geo"></i> {{ $log['city'] }}</small>
                                        </div>
                                    @endif
                                    
                                    @if(isset($log['isp']) && $log['isp'] !== 'unknown')
                                        <div class="mb-1">
                                            <small><i class="bi bi-wifi"></i> {{ Str::limit($log['isp'], 30) }}</small>
                                        </div>
                                    @endif
                                    
                                    @if(isset($log['platform']) && $log['platform'] !== 'unknown')
                                        <div class="mb-1">
                                            <small><i class="bi bi-laptop"></i> {{ $log['platform'] }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i>
                            No visitor logs found for the selected criteria.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let autoRefreshInterval;
        let isAutoRefreshOn = false;

        function toggleAutoRefresh() {
            if (isAutoRefreshOn) {
                clearInterval(autoRefreshInterval);
                isAutoRefreshOn = false;
                document.getElementById('refresh-status').textContent = 'OFF';
            } else {
                autoRefreshInterval = setInterval(() => {
                    window.location.reload();
                }, 30000); // Refresh every 30 seconds
                isAutoRefreshOn = true;
                document.getElementById('refresh-status').textContent = 'ON';
            }
        }

        // Auto-scroll to bottom for new entries
        window.addEventListener('load', function() {
            // Smooth scroll to show latest entries
            setTimeout(() => {
                window.scrollTo({
                    top: document.body.scrollHeight,
                    behavior: 'smooth'
                });
            }, 1000);
        });
    </script>
</body>
</html>
