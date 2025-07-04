<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING ADMIN SUBMISSIONS SHOW VIEW ===\n\n";

// Get a submission
$submission = Submission::with(['registration.user', 'registration.competition', 'files'])->first();

if (!$submission) {
    echo "No submission found for testing.\n";
    exit;
}

echo "Testing submission ID: {$submission->id}\n";
echo "Title: " . ($submission->title ?? 'No title') . "\n";
echo "Status: {$submission->status}\n";
echo "Registration ID: {$submission->registration_id}\n";

// Test if submission has all required relationships
echo "\n=== CHECKING RELATIONSHIPS ===\n";
echo "Registration: " . ($submission->registration ? "EXISTS" : "NULL") . "\n";
echo "User: " . ($submission->registration && $submission->registration->user ? "EXISTS" : "NULL") . "\n";
echo "Competition: " . ($submission->registration && $submission->registration->competition ? "EXISTS" : "NULL") . "\n";
echo "Scores: " . $submission->scores->count() . "\n";

// Test submission methods
echo "\n=== CHECKING SUBMISSION METHODS ===\n";
try {
    echo "Status Label: " . $submission->status_label . "\n";
    echo "Status Class: " . $submission->status_class . "\n";
    echo "File Size Formatted: " . $submission->file_size_formatted . "\n";
    echo "File Count: " . $submission->getFileCount() . "\n";
    echo "Has Files: " . ($submission->hasFiles() ? "YES" : "NO") . "\n";
    echo "Average Score: " . $submission->getAverageScore() . "\n";
    echo "Is Final: " . ($submission->isFinal() ? "YES" : "NO") . "\n";
} catch (Exception $e) {
    echo "Error testing submission methods: " . $e->getMessage() . "\n";
}

// Test view compilation
echo "\n=== TESTING VIEW COMPILATION ===\n";
try {
    $admin = User::role('Admin')->first();
    if ($admin) {
        Auth::login($admin);
        echo "Logged in as admin: {$admin->name}\n";
    }
    
    $view = view('admin.submissions.show', compact('submission'));
    $html = $view->render();
    echo "View compiled successfully!\n";
    echo "HTML length: " . strlen($html) . " characters\n";
    
    // Check if there are any obvious errors in the HTML
    if (strpos($html, 'error') !== false || strpos($html, 'Error') !== false) {
        echo "WARNING: Found 'error' text in rendered HTML\n";
    }
    
} catch (Exception $e) {
    echo "Error compiling view: " . $e->getMessage() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\nDone!\n";
