<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Competition;
use Illuminate\Support\Facades\Log;

class CompetitionStatusValidator
{
    /**
     * Handle an incoming request.
     * Validates competition status consistency across different views
     */
    public function handle(Request $request, Closure $next)
    {
        // Only run validation on competition-related routes
        if (!$this->shouldValidate($request)) {
            return $next($request);
        }

        $this->validateCompetitionStatus();

        return $next($request);
    }

    private function shouldValidate(Request $request): bool
    {
        $competitionRoutes = [
            'competitions',
            'peserta.dashboard',
            'admin.competitions',
            'admin.dashboard'
        ];

        return in_array($request->route()?->getName(), $competitionRoutes) ||
               str_contains($request->path(), 'competition') ||
               str_contains($request->path(), 'peserta') ||
               str_contains($request->path(), 'admin');
    }

    private function validateCompetitionStatus(): void
    {
        try {
            $competitions = Competition::all();
            $issues = [];

            foreach ($competitions as $competition) {
                // Check for inconsistent status
                if ($competition->is_active && $competition->status !== 'active') {
                    $issues[] = "Competition {$competition->id} ({$competition->name}): is_active=true but status='{$competition->status}'";
                }

                if (!$competition->is_active && $competition->status === 'active') {
                    $issues[] = "Competition {$competition->id} ({$competition->name}): is_active=false but status='active'";
                }

                // Check for invalid date ranges
                if ($competition->registration_start >= $competition->registration_end) {
                    $issues[] = "Competition {$competition->id} ({$competition->name}): Invalid registration date range";
                }

                if ($competition->competition_start >= $competition->competition_end) {
                    $issues[] = "Competition {$competition->id} ({$competition->name}): Invalid competition date range";
                }

                // Check for logical date sequence
                if ($competition->registration_end > $competition->competition_start) {
                    $issues[] = "Competition {$competition->id} ({$competition->name}): Registration ends after competition starts";
                }

                // Check early bird deadline
                if ($competition->early_bird_deadline > $competition->registration_end) {
                    $issues[] = "Competition {$competition->id} ({$competition->name}): Early bird deadline after registration end";
                }

                // Check submission dates if they exist
                if ($competition->submission_start && $competition->submission_end) {
                    if ($competition->submission_start >= $competition->submission_end) {
                        $issues[] = "Competition {$competition->id} ({$competition->name}): Invalid submission date range";
                    }
                }

                // Check team configuration
                if ($competition->is_team_competition) {
                    if ($competition->min_team_members > $competition->max_team_members) {
                        $issues[] = "Competition {$competition->id} ({$competition->name}): min_team_members > max_team_members";
                    }

                    if (!$competition->allow_individual && $competition->min_team_members < 2) {
                        $issues[] = "Competition {$competition->id} ({$competition->name}): Individual not allowed but min_team_members < 2";
                    }
                }

                // Check pricing
                if ($competition->early_bird_price >= $competition->price) {
                    $issues[] = "Competition {$competition->id} ({$competition->name}): Early bird price >= regular price";
                }
            }

            // Log issues if found
            if (!empty($issues)) {
                Log::warning('Competition Status Validation Issues Found:', $issues);
                
                // In development, you might want to throw an exception
                if (config('app.debug')) {
                    \Illuminate\Support\Facades\Session::flash('competition_validation_issues', $issues);
                }
            }

        } catch (\Exception $e) {
            Log::error('Competition Status Validation Error: ' . $e->getMessage());
        }
    }
}
