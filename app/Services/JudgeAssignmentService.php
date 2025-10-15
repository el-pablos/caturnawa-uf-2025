<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\Submission;
use App\Models\User;
use App\Models\Judging;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service for managing judge assignments to submissions
 * 
 * Handles automated assignment with workload balancing,
 * conflict of interest detection, and manual overrides
 */
class JudgeAssignmentService
{
    /**
     * Automatically assign judges to all unassigned submissions in a competition
     *
     * @param Competition $competition
     * @param int $judgesPerSubmission Number of judges to assign per submission (default: 3)
     * @return array Assignment results
     */
    public function autoAssignJudges(Competition $competition, int $judgesPerSubmission = 3): array
    {
        DB::beginTransaction();
        
        try {
            // Get all active judges for this competition
            $judges = $this->getAvailableJudges($competition);
            
            if ($judges->count() < $judgesPerSubmission) {
                throw new \Exception("Not enough judges available. Need at least {$judgesPerSubmission} judges.");
            }
            
            // Get all submissions that need judges
            $submissions = $competition->submissions()
                ->whereHas('registration', function ($q) {
                    $q->where('status', 'confirmed');
                })
                ->where('is_final', true)
                ->get();
            
            if ($submissions->isEmpty()) {
                throw new \Exception("No submissions found for this competition.");
            }
            
            $assignments = [];
            $judgeWorkload = $judges->mapWithKeys(function ($judge) {
                return [$judge->id => 0];
            });
            
            foreach ($submissions as $submission) {
                // Get already assigned judges for this submission
                $existingJudges = Judging::where('submission_id', $submission->id)
                    ->pluck('jury_id')
                    ->toArray();
                
                $neededJudges = $judgesPerSubmission - count($existingJudges);
                
                if ($neededJudges <= 0) {
                    continue; // Already has enough judges
                }
                
                // Get judges with lowest workload who aren't already assigned
                $availableJudges = $judges->filter(function ($judge) use ($existingJudges, $submission) {
                    // Check if already assigned
                    if (in_array($judge->id, $existingJudges)) {
                        return false;
                    }
                    
                    // Check for conflict of interest (same institution)
                    if ($this->hasConflictOfInterest($judge, $submission)) {
                        return false;
                    }
                    
                    return true;
                });
                
                // Sort by workload (ascending)
                $selectedJudges = $availableJudges->sortBy(function ($judge) use ($judgeWorkload) {
                    return $judgeWorkload[$judge->id];
                })->take($neededJudges);
                
                // Assign judges
                foreach ($selectedJudges as $judge) {
                    Judging::create([
                        'submission_id' => $submission->id,
                        'jury_id' => $judge->id,
                        'score' => null,
                        'feedback' => null,
                        'judged_at' => null,
                    ]);
                    
                    $judgeWorkload[$judge->id]++;
                    
                    $assignments[] = [
                        'submission_id' => $submission->id,
                        'submission_title' => $submission->title,
                        'judge_id' => $judge->id,
                        'judge_name' => $judge->name,
                    ];
                }
            }
            
            DB::commit();
            
            Log::info('Auto-assigned judges to submissions', [
                'competition_id' => $competition->id,
                'total_assignments' => count($assignments),
            ]);
            
            return [
                'success' => true,
                'message' => 'Successfully assigned ' . count($assignments) . ' judges to submissions',
                'assignments' => $assignments,
                'workload' => $judgeWorkload->toArray(),
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to auto-assign judges', [
                'competition_id' => $competition->id,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to assign judges: ' . $e->getMessage(),
                'assignments' => [],
            ];
        }
    }
    
    /**
     * Manually assign a judge to a submission
     *
     * @param Submission $submission
     * @param User $judge
     * @return array
     */
    public function assignJudge(Submission $submission, User $judge): array
    {
        try {
            // Check if judge has the 'juri' role
            if (!$judge->hasRole('juri')) {
                throw new \Exception("User is not a judge.");
            }
            
            // Check if already assigned
            $existing = Judging::where('submission_id', $submission->id)
                ->where('jury_id', $judge->id)
                ->first();
            
            if ($existing) {
                throw new \Exception("Judge is already assigned to this submission.");
            }
            
            // Check for conflict of interest
            if ($this->hasConflictOfInterest($judge, $submission)) {
                throw new \Exception("Conflict of interest detected: Judge and participant are from the same institution.");
            }
            
            // Create assignment
            Judging::create([
                'submission_id' => $submission->id,
                'jury_id' => $judge->id,
                'score' => null,
                'feedback' => null,
                'judged_at' => null,
            ]);
            
            Log::info('Manually assigned judge to submission', [
                'submission_id' => $submission->id,
                'judge_id' => $judge->id,
            ]);
            
            return [
                'success' => true,
                'message' => 'Judge assigned successfully',
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to assign judge', [
                'submission_id' => $submission->id,
                'judge_id' => $judge->id,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Remove a judge assignment
     *
     * @param Submission $submission
     * @param User $judge
     * @return array
     */
    public function unassignJudge(Submission $submission, User $judge): array
    {
        try {
            $judging = Judging::where('submission_id', $submission->id)
                ->where('jury_id', $judge->id)
                ->first();
            
            if (!$judging) {
                throw new \Exception("Judge is not assigned to this submission.");
            }
            
            // Don't allow removal if already judged
            if ($judging->judged_at) {
                throw new \Exception("Cannot remove judge who has already submitted a score.");
            }
            
            $judging->delete();
            
            Log::info('Removed judge assignment', [
                'submission_id' => $submission->id,
                'judge_id' => $judge->id,
            ]);
            
            return [
                'success' => true,
                'message' => 'Judge assignment removed successfully',
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to remove judge assignment', [
                'submission_id' => $submission->id,
                'judge_id' => $judge->id,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Get available judges for a competition
     *
     * @param Competition $competition
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableJudges(Competition $competition)
    {
        // Get judges assigned to this competition
        $judges = $competition->juries()
            ->where('is_active', true)
            ->get();
        
        // If no specific judges assigned, get all active judges
        if ($judges->isEmpty()) {
            $judges = User::role('juri')
                ->where('is_active', true)
                ->get();
        }
        
        return $judges;
    }
    
    /**
     * Get judge workload statistics
     *
     * @param Competition $competition
     * @return array
     */
    public function getJudgeWorkload(Competition $competition): array
    {
        $judges = $this->getAvailableJudges($competition);
        
        $workload = [];
        
        foreach ($judges as $judge) {
            $totalAssignments = Judging::whereHas('submission.registration', function ($q) use ($competition) {
                $q->where('competition_id', $competition->id);
            })->where('jury_id', $judge->id)->count();
            
            $completedAssignments = Judging::whereHas('submission.registration', function ($q) use ($competition) {
                $q->where('competition_id', $competition->id);
            })->where('jury_id', $judge->id)
              ->whereNotNull('judged_at')
              ->count();
            
            $workload[] = [
                'judge_id' => $judge->id,
                'judge_name' => $judge->name,
                'total_assignments' => $totalAssignments,
                'completed_assignments' => $completedAssignments,
                'pending_assignments' => $totalAssignments - $completedAssignments,
                'completion_rate' => $totalAssignments > 0 ? round(($completedAssignments / $totalAssignments) * 100, 2) : 0,
            ];
        }
        
        return $workload;
    }
    
    /**
     * Check for conflict of interest between judge and submission
     *
     * @param User $judge
     * @param Submission $submission
     * @return bool
     */
    protected function hasConflictOfInterest(User $judge, Submission $submission): bool
    {
        $participant = $submission->registration->user;
        
        // Check if same institution
        if ($judge->institution && $participant->institution) {
            if (strtolower(trim($judge->institution)) === strtolower(trim($participant->institution))) {
                return true;
            }
        }
        
        // Add more conflict checks here if needed
        // e.g., same department, advisor-student relationship, etc.
        
        return false;
    }
}

