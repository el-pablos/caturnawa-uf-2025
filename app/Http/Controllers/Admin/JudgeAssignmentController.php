<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Submission;
use App\Models\User;
use App\Services\JudgeAssignmentService;
use Illuminate\Http\Request;

/**
 * Controller for managing judge assignments
 */
class JudgeAssignmentController extends Controller
{
    protected JudgeAssignmentService $assignmentService;
    
    public function __construct(JudgeAssignmentService $assignmentService)
    {
        $this->middleware(['auth', 'role:admin']);
        $this->assignmentService = $assignmentService;
    }
    
    /**
     * Display judge assignment management page
     */
    public function index(Request $request)
    {
        $competitions = Competition::where('status', 'active')
            ->orWhere('status', 'ongoing')
            ->orderBy('start_date', 'desc')
            ->get();
        
        $selectedCompetition = null;
        $submissions = collect();
        $judges = collect();
        $workload = [];
        
        if ($request->has('competition_id')) {
            $selectedCompetition = Competition::findOrFail($request->competition_id);
            
            // Get submissions with judge assignments
            $submissions = $selectedCompetition->submissions()
                ->with(['registration.user', 'judgings.jury'])
                ->where('is_final', true)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Get available judges
            $judges = $this->assignmentService->getAvailableJudges($selectedCompetition);
            
            // Get workload statistics
            $workload = $this->assignmentService->getJudgeWorkload($selectedCompetition);
        }
        
        return view('admin.judge-assignment.index', compact(
            'competitions',
            'selectedCompetition',
            'submissions',
            'judges',
            'workload'
        ));
    }
    
    /**
     * Auto-assign judges to all submissions
     */
    public function autoAssign(Request $request)
    {
        $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'judges_per_submission' => 'required|integer|min:1|max:5',
        ]);
        
        $competition = Competition::findOrFail($request->competition_id);
        
        $result = $this->assignmentService->autoAssignJudges(
            $competition,
            $request->judges_per_submission
        );
        
        if ($result['success']) {
            return redirect()
                ->route('admin.judge-assignment.index', ['competition_id' => $competition->id])
                ->with('success', $result['message']);
        } else {
            return redirect()
                ->back()
                ->with('error', $result['message']);
        }
    }
    
    /**
     * Manually assign a judge to a submission
     */
    public function assign(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'judge_id' => 'required|exists:users,id',
        ]);
        
        $submission = Submission::findOrFail($request->submission_id);
        $judge = User::findOrFail($request->judge_id);
        
        $result = $this->assignmentService->assignJudge($submission, $judge);
        
        if ($result['success']) {
            return redirect()
                ->back()
                ->with('success', $result['message']);
        } else {
            return redirect()
                ->back()
                ->with('error', $result['message']);
        }
    }
    
    /**
     * Remove a judge assignment
     */
    public function unassign(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'judge_id' => 'required|exists:users,id',
        ]);
        
        $submission = Submission::findOrFail($request->submission_id);
        $judge = User::findOrFail($request->judge_id);
        
        $result = $this->assignmentService->unassignJudge($submission, $judge);
        
        if ($result['success']) {
            return redirect()
                ->back()
                ->with('success', $result['message']);
        } else {
            return redirect()
                ->back()
                ->with('error', $result['message']);
        }
    }
    
    /**
     * Get judge workload via AJAX
     */
    public function getWorkload(Request $request)
    {
        $request->validate([
            'competition_id' => 'required|exists:competitions,id',
        ]);
        
        $competition = Competition::findOrFail($request->competition_id);
        $workload = $this->assignmentService->getJudgeWorkload($competition);
        
        return response()->json([
            'success' => true,
            'workload' => $workload,
        ]);
    }
}

