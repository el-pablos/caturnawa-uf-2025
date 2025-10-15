<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

/**
 * Controller for viewing and managing activity logs
 */
class ActivityLogController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->middleware(['auth', 'role:admin|superadmin']);
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'causer']);

        // Filter by log name (category)
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // Order by latest
        $query->orderBy('created_at', 'desc');

        // Paginate
        $logs = $query->paginate(50);

        // Get statistics
        $statistics = $this->activityLogService->getStatistics();

        // Get unique log names for filter
        $logNames = ActivityLog::select('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name');

        return view('admin.activity-logs.index', compact('logs', 'statistics', 'logNames'));
    }

    /**
     * Show single activity log
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load(['user', 'causer', 'subject']);

        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Clean old logs
     */
    public function clean(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30|max:365',
        ]);

        $deletedCount = $this->activityLogService->cleanOldLogs($request->days);

        // Log this admin action
        $this->activityLogService->logAdmin(
            "Cleaned {$deletedCount} activity logs older than {$request->days} days",
            null,
            'clean',
            ['deleted_count' => $deletedCount, 'days' => $request->days]
        );

        return redirect()->route('admin.activity-logs.index')
            ->with('success', "Successfully deleted {$deletedCount} old activity logs.");
    }

    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with(['user', 'causer']);

        // Apply same filters as index
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'activity_logs_' . date('YmdHis') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($file, [
                'ID',
                'Date & Time',
                'Category',
                'User',
                'Description',
                'Event',
                'Subject Type',
                'Subject ID',
                'Causer',
                'IP Address',
            ]);

            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->log_name ?? '-',
                    $log->user ? $log->user->name : '-',
                    $log->description,
                    $log->event ?? '-',
                    $log->subject_type ? class_basename($log->subject_type) : '-',
                    $log->subject_id ?? '-',
                    $log->causer ? $log->causer->name : '-',
                    $log->ip_address ?? '-',
                ]);
            }

            fclose($file);
        };

        // Log this export action
        $this->activityLogService->logExport(
            'Exported ' . $logs->count() . ' activity logs to CSV',
            ['count' => $logs->count(), 'filters' => $request->all()]
        );

        return response()->stream($callback, 200, $headers);
    }
}

