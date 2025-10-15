<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Services\ExportService;
use Illuminate\Http\Request;

/**
 * Controller for data export functionality
 */
class ExportController extends Controller
{
    protected ExportService $exportService;
    
    public function __construct(ExportService $exportService)
    {
        $this->middleware(['auth', 'role:admin']);
        $this->exportService = $exportService;
    }
    
    /**
     * Display export management page
     */
    public function index()
    {
        $competitions = Competition::orderBy('start_date', 'desc')->get();
        
        return view('admin.export.index', compact('competitions'));
    }
    
    /**
     * Export registrations
     */
    public function exportRegistrations(Request $request)
    {
        $competition = null;
        
        if ($request->filled('competition_id')) {
            $competition = Competition::find($request->competition_id);
        }
        
        $filters = [
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ];
        
        return $this->exportService->exportRegistrations($competition, $filters);
    }
    
    /**
     * Export payments
     */
    public function exportPayments(Request $request)
    {
        $competition = null;
        
        if ($request->filled('competition_id')) {
            $competition = Competition::find($request->competition_id);
        }
        
        $filters = [
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ];
        
        return $this->exportService->exportPayments($competition, $filters);
    }
    
    /**
     * Export submissions
     */
    public function exportSubmissions(Request $request)
    {
        $competition = null;
        
        if ($request->filled('competition_id')) {
            $competition = Competition::find($request->competition_id);
        }
        
        $filters = [
            'is_final' => $request->is_final,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ];
        
        return $this->exportService->exportSubmissions($competition, $filters);
    }
    
    /**
     * Export scores
     */
    public function exportScores(Request $request)
    {
        $competition = null;
        
        if ($request->filled('competition_id')) {
            $competition = Competition::find($request->competition_id);
        }
        
        $filters = [
            'is_final' => $request->is_final,
        ];
        
        return $this->exportService->exportScores($competition, $filters);
    }
}

