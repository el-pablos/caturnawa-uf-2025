<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Service for logging user activities and system events
 */
class ActivityLogService
{
    /**
     * Log an activity
     *
     * @param string $logName Category (auth, registration, payment, etc.)
     * @param string $description Human-readable description
     * @param mixed $subject The model being acted upon
     * @param string|null $event Event type (created, updated, deleted, etc.)
     * @param array $properties Additional data
     * @param User|null $causer Who caused this action
     * @return ActivityLog
     */
    public function log(
        string $logName,
        string $description,
        $subject = null,
        ?string $event = null,
        array $properties = [],
        ?User $causer = null
    ): ActivityLog {
        $causer = $causer ?? Auth::user();
        
        $logData = [
            'log_name' => $logName,
            'description' => $description,
            'event' => $event,
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ];

        // Set subject
        if ($subject) {
            $logData['subject_type'] = get_class($subject);
            $logData['subject_id'] = $subject->id ?? null;
            $logData['user_id'] = $subject->user_id ?? null;
        }

        // Set causer
        if ($causer) {
            $logData['causer_type'] = get_class($causer);
            $logData['causer_id'] = $causer->id;
            
            // If no subject user_id, use causer as user
            if (!isset($logData['user_id'])) {
                $logData['user_id'] = $causer->id;
            }
        }

        return ActivityLog::create($logData);
    }

    /**
     * Log authentication event
     */
    public function logAuth(string $description, ?User $user = null, array $properties = []): ActivityLog
    {
        return $this->log('auth', $description, $user, 'auth', $properties, $user);
    }

    /**
     * Log registration event
     */
    public function logRegistration($registration, string $event, string $description, array $properties = []): ActivityLog
    {
        return $this->log('registration', $description, $registration, $event, $properties);
    }

    /**
     * Log payment event
     */
    public function logPayment($payment, string $event, string $description, array $properties = []): ActivityLog
    {
        return $this->log('payment', $description, $payment, $event, $properties);
    }

    /**
     * Log submission event
     */
    public function logSubmission($submission, string $event, string $description, array $properties = []): ActivityLog
    {
        return $this->log('submission', $description, $submission, $event, $properties);
    }

    /**
     * Log scoring event
     */
    public function logScoring($score, string $event, string $description, array $properties = []): ActivityLog
    {
        return $this->log('scoring', $description, $score, $event, $properties);
    }

    /**
     * Log admin action
     */
    public function logAdmin(string $description, $subject = null, string $event = 'action', array $properties = []): ActivityLog
    {
        return $this->log('admin', $description, $subject, $event, $properties);
    }

    /**
     * Log debate event
     */
    public function logDebate($debateMatch, string $event, string $description, array $properties = []): ActivityLog
    {
        return $this->log('debate', $description, $debateMatch, $event, $properties);
    }

    /**
     * Log certificate generation
     */
    public function logCertificate($registration, string $description, array $properties = []): ActivityLog
    {
        return $this->log('certificate', $description, $registration, 'generated', $properties);
    }

    /**
     * Log export action
     */
    public function logExport(string $description, array $properties = []): ActivityLog
    {
        return $this->log('export', $description, null, 'exported', $properties);
    }

    /**
     * Get recent activities
     *
     * @param int $limit
     * @param string|null $logName
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentActivities(int $limit = 50, ?string $logName = null)
    {
        $query = ActivityLog::with(['user', 'causer'])
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if ($logName) {
            $query->where('log_name', $logName);
        }

        return $query->get();
    }

    /**
     * Get activities for a specific user
     *
     * @param int $userId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserActivities(int $userId, int $limit = 50)
    {
        return ActivityLog::with(['user', 'causer'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activities by date range
     *
     * @param string $startDate
     * @param string|null $endDate
     * @param string|null $logName
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActivitiesByDateRange(string $startDate, ?string $endDate = null, ?string $logName = null)
    {
        $query = ActivityLog::with(['user', 'causer'])
            ->whereDate('created_at', '>=', $startDate);

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($logName) {
            $query->where('log_name', $logName);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get activity statistics
     *
     * @return array
     */
    public function getStatistics(): array
    {
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        return [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', '>=', $today)->count(),
            'this_week' => ActivityLog::whereDate('created_at', '>=', $thisWeek)->count(),
            'this_month' => ActivityLog::whereDate('created_at', '>=', $thisMonth)->count(),
            'by_category' => ActivityLog::selectRaw('log_name, COUNT(*) as count')
                ->groupBy('log_name')
                ->pluck('count', 'log_name')
                ->toArray(),
        ];
    }

    /**
     * Clean old logs (older than specified days)
     *
     * @param int $days
     * @return int Number of deleted logs
     */
    public function cleanOldLogs(int $days = 90): int
    {
        $cutoffDate = now()->subDays($days);
        
        return ActivityLog::where('created_at', '<', $cutoffDate)->delete();
    }
}

