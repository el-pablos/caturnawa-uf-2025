<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    /**
     * Clear all application caches
     */
    public function clearCache()
    {
        try {
            // Clear various caches
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            // Clear compiled views
            if (File::exists(storage_path('framework/views'))) {
                File::cleanDirectory(storage_path('framework/views'));
            }
            
            // Clear compiled services
            if (File::exists(storage_path('framework/cache/services.php'))) {
                File::delete(storage_path('framework/cache/services.php'));
            }
            
            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Cache clear failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Optimize application performance
     */
    public function optimize()
    {
        try {
            // Run optimization commands
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            
            // Optimize autoloader
            if (app()->environment('production')) {
                Artisan::call('optimize');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Application optimized successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Optimization failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to optimize application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear old log files
     */
    public function clearLogs()
    {
        try {
            $logPath = storage_path('logs');
            $files = File::files($logPath);
            $deletedCount = 0;
            
            foreach ($files as $file) {
                // Delete log files older than 30 days
                if ($file->getMTime() < strtotime('-30 days')) {
                    File::delete($file->getPathname());
                    $deletedCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Cleared {$deletedCount} old log files"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Log cleanup failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Run comprehensive maintenance tasks
     */
    public function runAll()
    {
        try {
            $results = [];
            
            // Clear caches
            try {
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
                $results['cache'] = 'success';
            } catch (\Exception $e) {
                $results['cache'] = 'failed: ' . $e->getMessage();
            }
            
            // Optimize application
            try {
                Artisan::call('config:cache');
                Artisan::call('route:cache');
                Artisan::call('view:cache');
                $results['optimize'] = 'success';
            } catch (\Exception $e) {
                $results['optimize'] = 'failed: ' . $e->getMessage();
            }
            
            // Clear old logs
            try {
                $logPath = storage_path('logs');
                $files = File::files($logPath);
                $deletedCount = 0;
                
                foreach ($files as $file) {
                    if ($file->getMTime() < strtotime('-30 days')) {
                        File::delete($file->getPathname());
                        $deletedCount++;
                    }
                }
                $results['logs'] = "cleared {$deletedCount} old files";
            } catch (\Exception $e) {
                $results['logs'] = 'failed: ' . $e->getMessage();
            }
            
            // Clean temporary files
            try {
                if (File::exists(storage_path('framework/cache'))) {
                    $tempFiles = File::files(storage_path('framework/cache'));
                    $cleanedCount = 0;
                    
                    foreach ($tempFiles as $file) {
                        if ($file->getMTime() < strtotime('-1 day')) {
                            File::delete($file->getPathname());
                            $cleanedCount++;
                        }
                    }
                }
                $results['temp_files'] = "cleaned {$cleanedCount} temporary files";
            } catch (\Exception $e) {
                $results['temp_files'] = 'failed: ' . $e->getMessage();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Comprehensive maintenance completed',
                'details' => $results
            ]);
            
        } catch (\Exception $e) {
            Log::error('Comprehensive maintenance failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Maintenance failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check system health
     */
    public function healthCheck()
    {
        try {
            $health = [
                'database' => false,
                'redis' => false,
                'storage' => false,
                'queue' => false
            ];
            
            // Check database connection
            try {
                DB::connection()->getPdo();
                $health['database'] = true;
            } catch (\Exception $e) {
                Log::warning('Database health check failed: ' . $e->getMessage());
            }
            
            // Check Redis connection (if configured)
            try {
                if (config('database.redis.default.host')) {
                    Redis::ping();
                    $health['redis'] = true;
                }
            } catch (\Exception $e) {
                Log::warning('Redis health check failed: ' . $e->getMessage());
            }
            
            // Check storage accessibility
            try {
                Storage::disk('public')->put('health-check.txt', 'OK');
                Storage::disk('public')->delete('health-check.txt');
                $health['storage'] = true;
            } catch (\Exception $e) {
                Log::warning('Storage health check failed: ' . $e->getMessage());
            }
            
            // Check queue system
            try {
                // Simple check - if we can access the jobs table
                DB::table('jobs')->count();
                $health['queue'] = true;
            } catch (\Exception $e) {
                Log::warning('Queue health check failed: ' . $e->getMessage());
            }
            
            return response()->json([
                'success' => true,
                'message' => 'System health check completed',
                'data' => $health
            ]);
            
        } catch (\Exception $e) {
            Log::error('Health check failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Health check failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
