<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::get('/achievements', [App\Http\Controllers\Api\AchievementController::class, 'index']);
Route::get('/leaderboard', [App\Http\Controllers\Api\LeaderboardController::class, 'index']);
Route::get('/competitions', [App\Http\Controllers\Api\CompetitionController::class, 'index']);
Route::get('/competitions/{competition}', [App\Http\Controllers\Api\CompetitionController::class, 'show']);
Route::get('/competitions/{competition}/description/{section?}', [App\Http\Controllers\Api\CompetitionController::class, 'getDescription']);
Route::get('/submissions/{submission}', [App\Http\Controllers\Api\SubmissionController::class, 'show']);

// Judging API (requires authentication)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/judging/form', [App\Http\Controllers\Api\JudgingController::class, 'getForm']);
    Route::post('/judging/score', [App\Http\Controllers\Api\JudgingController::class, 'saveScore']);
});

// Invoice API for Finance Department (requires admin or finance role)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/invoices', [App\Http\Controllers\Api\InvoiceController::class, 'index']);
    Route::get('/invoices/{payment_id}', [App\Http\Controllers\Api\InvoiceController::class, 'show']);
});

// User Session and Authenticated Routes (using web middleware for session-based auth)
Route::middleware(['web', 'auth'])->group(function () {
    // User session with deadline reminders
    Route::get('/user/session', [App\Http\Controllers\Api\UserSessionController::class, 'getSession']);
    Route::post('/user/dismiss-reminder', [App\Http\Controllers\Api\UserSessionController::class, 'dismissReminder']);
});

// Registration Documents (requires sanctum auth)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/registrations/{registration}/documents', [App\Http\Controllers\Api\RegistrationDocumentController::class, 'index']);
    Route::post('/registrations/{registration}/documents', [App\Http\Controllers\Api\RegistrationDocumentController::class, 'store']);
    Route::delete('/registrations/{registration}/documents/{document}', [App\Http\Controllers\Api\RegistrationDocumentController::class, 'destroy']);
});

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/leaderboard/update', [App\Http\Controllers\Api\LeaderboardController::class, 'update']);
});

// API Routes untuk AJAX calls
Route::middleware(['auth'])->group(function () {
    
    // Competition API
    Route::get('/competitions', [App\Http\Controllers\Api\CompetitionController::class, 'index']);
    Route::get('/competitions/{competition}', [App\Http\Controllers\Api\CompetitionController::class, 'show']);
    
    // Registration API
    Route::get('/registrations', [App\Http\Controllers\Api\RegistrationController::class, 'index']);
    Route::get('/registrations/datatable', [App\Http\Controllers\Api\RegistrationController::class, 'datatable']);
    
    // Payment API
    Route::get('/payments', [App\Http\Controllers\Api\PaymentController::class, 'index']);
    Route::get('/payments/datatable', [App\Http\Controllers\Api\PaymentController::class, 'datatable']);
    
    // User API (Admin only)
    Route::middleware(['role:superadmin|admin'])->group(function () {
        Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'index']);
        Route::get('/users/datatable', [App\Http\Controllers\Api\UserController::class, 'datatable']);
    });
    
    // Statistics API
    Route::get('/statistics/dashboard', [App\Http\Controllers\Api\StatisticsController::class, 'dashboard']);
    Route::get('/statistics/competition/{competition}', [App\Http\Controllers\Api\StatisticsController::class, 'competition']);

    // Debate Tournament API (Admin only)
    Route::middleware(['role:admin'])->prefix('debate')->group(function () {
        // Tournament Generation
        Route::post('/tournament/generate', [App\Http\Controllers\Api\DebateTournamentController::class, 'generate']);
        Route::get('/tournament/status', [App\Http\Controllers\Api\DebateTournamentController::class, 'status']);

        // Rounds Management
        Route::get('/rounds', [App\Http\Controllers\Api\DebateTournamentController::class, 'rounds']);
        Route::get('/rounds/{round}', [App\Http\Controllers\Api\DebateTournamentController::class, 'showRound']);
        Route::post('/rounds/{round}/freeze', [App\Http\Controllers\Api\DebateTournamentController::class, 'freezeRound']);
        Route::post('/rounds/{round}/unfreeze', [App\Http\Controllers\Api\DebateTournamentController::class, 'unfreezeRound']);

        // Matches Management
        Route::get('/matches', [App\Http\Controllers\Api\DebateTournamentController::class, 'matches']);
        Route::get('/matches/{match}', [App\Http\Controllers\Api\DebateTournamentController::class, 'showMatch']);
        Route::put('/matches/{match}', [App\Http\Controllers\Api\DebateTournamentController::class, 'updateMatch']);
        Route::post('/matches/{match}/assign-judge', [App\Http\Controllers\Api\DebateTournamentController::class, 'assignJudge']);

        // Standings
        Route::get('/standings', [App\Http\Controllers\Api\DebateTournamentController::class, 'standings']);
        Route::post('/standings/recalculate', [App\Http\Controllers\Api\DebateTournamentController::class, 'recalculateStandings']);
        Route::get('/standings/export', [App\Http\Controllers\Api\DebateTournamentController::class, 'exportStandings']);
        Route::get('/speaker-standings', [App\Http\Controllers\Api\DebateTournamentController::class, 'speakerStandings']);
    });

    // Debate Scoring API (Judge only)
    Route::middleware(['role:judge'])->prefix('judge/debate')->group(function () {
        Route::get('/matches', [App\Http\Controllers\Api\DebateScoringController::class, 'getMatches']);
        Route::get('/matches/{match}', [App\Http\Controllers\Api\DebateScoringController::class, 'getMatch']);
        Route::post('/matches/{match}/scores', [App\Http\Controllers\Api\DebateScoringController::class, 'submitScores']);
        Route::get('/matches/{match}/scores', [App\Http\Controllers\Api\DebateScoringController::class, 'getScores']);
    });
});
