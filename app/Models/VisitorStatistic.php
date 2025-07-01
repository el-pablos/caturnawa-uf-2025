<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VisitorStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_url',
        'referrer',
        'country',
        'city',
        'is_unique_today',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'is_unique_today' => 'boolean',
    ];

    /**
     * Get today's unique visitors count
     */
    public static function getTodayVisitors()
    {
        return self::whereDate('visited_at', Carbon::today())
                   ->where('is_unique_today', true)
                   ->count();
    }

    /**
     * Get this week's unique visitors count
     */
    public static function getThisWeekVisitors()
    {
        return self::whereBetween('visited_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])
                ->where('is_unique_today', true)
                ->count();
    }

    /**
     * Get total unique visitors count
     */
    public static function getTotalVisitors()
    {
        return self::where('is_unique_today', true)->count();
    }

    /**
     * Record a new visitor
     */
    public static function recordVisitor($request)
    {
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        $pageUrl = $request->fullUrl();
        $referrer = $request->header('referer');

        // Check if this IP has visited today
        $existingToday = self::where('ip_address', $ipAddress)
                            ->whereDate('visited_at', Carbon::today())
                            ->exists();

        return self::create([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'page_url' => $pageUrl,
            'referrer' => $referrer,
            'is_unique_today' => !$existingToday,
            'visited_at' => now(),
        ]);
    }

    /**
     * Get visitor statistics for footer
     */
    public static function getFooterStats()
    {
        return [
            'today' => self::getTodayVisitors(),
            'this_week' => self::getThisWeekVisitors(),
            'total' => self::getTotalVisitors(),
        ];
    }
}
