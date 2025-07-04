<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Submission;
use App\Models\Registration;
use App\Models\Competition;
use App\Models\User;
use App\Models\Score;

echo "=== DEBUG JURY SUBMISSIONS ISSUE ===\n\n";

// Check if there are any submissions
$submissions = Submission::with(['registration.user', 'registration.competition'])->get();
echo "Total submissions: " . $submissions->count() . "\n\n";

// Check if there are any users with Juri role
$juries = User::role('Juri')->get();
echo "Total juries: " . $juries->count() . "\n";
foreach ($juries as $jury) {
    echo "- {$jury->name} (ID: {$jury->id})\n";
}
echo "\n";

// Check if there are any competitions
$competitions = Competition::with('juries')->get();
echo "Total competitions: " . $competitions->count() . "\n";
foreach ($competitions as $competition) {
    echo "- {$competition->name} (ID: {$competition->id})\n";
    echo "  Assigned juries: " . $competition->juries->count() . "\n";
    foreach ($competition->juries as $jury) {
        echo "    - {$jury->name} (ID: {$jury->id})\n";
    }
    echo "\n";
}

// Check scores
$scores = Score::with(['jury', 'registration.user'])->get();
echo "Total scores: " . $scores->count() . "\n";
foreach ($scores as $score) {
    echo "- Jury: " . ($score->jury ? $score->jury->name : 'NULL') . "\n";
    echo "  Participant: " . ($score->registration && $score->registration->user ? $score->registration->user->name : 'NULL') . "\n";
    echo "  Score: {$score->total_score}\n";
    echo "  Final: " . ($score->is_final ? 'Yes' : 'No') . "\n\n";
}

// Check for specific submission issue
echo "=== CHECKING SPECIFIC SUBMISSIONS ===\n";
foreach ($submissions as $submission) {
    echo "Submission ID: {$submission->id}\n";
    echo "Title: " . ($submission->title ?? 'No title') . "\n";
    echo "Registration ID: {$submission->registration_id}\n";
    echo "Competition: " . ($submission->registration && $submission->registration->competition ? $submission->registration->competition->name : 'NULL') . "\n";
    echo "Participant: " . ($submission->registration && $submission->registration->user ? $submission->registration->user->name : 'NULL') . "\n";
    
    // Check scores for this submission
    $submissionScores = $submission->scores;
    echo "Scores count: " . $submissionScores->count() . "\n";
    
    foreach ($submissionScores as $score) {
        echo "  - Jury: " . ($score->jury ? $score->jury->name : 'NULL') . "\n";
        echo "    Score: {$score->total_score}\n";
        echo "    Final: " . ($score->is_final ? 'Yes' : 'No') . "\n";
    }
    echo "---\n";
}

echo "\n=== CHECKING SCORE RELATIONSHIPS ===\n";
// Check for broken relationships
$brokenScores = Score::whereNull('jury_id')
    ->orWhereNotExists(function ($query) {
        $query->select('id')
            ->from('users')
            ->whereColumn('users.id', 'scores.jury_id');
    })
    ->get();

echo "Broken scores (no jury or invalid jury): " . $brokenScores->count() . "\n";
foreach ($brokenScores as $score) {
    echo "- Score ID: {$score->id}, Jury ID: {$score->jury_id}\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
if ($juries->count() === 0) {
    echo "1. No juries found. Please create users with 'Juri' role.\n";
}

if ($competitions->count() > 0) {
    $competitionsWithoutJuries = $competitions->filter(function ($comp) {
        return $comp->juries->count() === 0;
    });
    
    if ($competitionsWithoutJuries->count() > 0) {
        echo "2. The following competitions have no assigned juries:\n";
        foreach ($competitionsWithoutJuries as $comp) {
            echo "   - {$comp->name} (ID: {$comp->id})\n";
        }
    }
}

if ($brokenScores->count() > 0) {
    echo "3. There are broken score records that need to be fixed.\n";
}

echo "\nDone!\n";
