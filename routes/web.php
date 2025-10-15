<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Health Check Route
Route::get('/health', function () {
    $checks = [
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'services' => []
    ];

    try {
        // Database check
        DB::connection()->getPdo();
        $checks['services']['database'] = 'healthy';
    } catch (Exception $e) {
        $checks['services']['database'] = 'unhealthy';
        $checks['status'] = 'unhealthy';
    }

    try {
        // Redis check
        Cache::store('redis')->put('health_check', 'ok', 10);
        $checks['services']['redis'] = 'healthy';
    } catch (Exception $e) {
        $checks['services']['redis'] = 'unhealthy';
        $checks['status'] = 'unhealthy';
    }

    // Storage check
    if (is_writable(storage_path())) {
        $checks['services']['storage'] = 'healthy';
    } else {
        $checks['services']['storage'] = 'unhealthy';
        $checks['status'] = 'unhealthy';
    }

    $statusCode = $checks['status'] === 'healthy' ? 200 : 503;

    return response()->json($checks, $statusCode);
})->name('health');

// SEO Routes
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [App\Http\Controllers\SitemapController::class, 'robots'])->name('robots');

// Public Pages Routes (Main Website)
Route::name('public.')->middleware('maintenance')->group(function () {
    // Home page - accessible via root and /home
    Route::get('/', [App\Http\Controllers\Public\PublicController::class, 'home'])->name('home');
    Route::get('/home', [App\Http\Controllers\Public\PublicController::class, 'home'])->name('home.alt');

    // Competition pages
    Route::get('/competitions', [App\Http\Controllers\Public\PublicController::class, 'competitions'])->name('competitions');
    Route::get('/competition/{slug}', [App\Http\Controllers\Public\PublicController::class, 'competitionDetail'])->name('competition.detail');

    // About and team pages
    Route::get('/about', [App\Http\Controllers\Public\PublicController::class, 'about'])->name('about');
    Route::get('/team', [App\Http\Controllers\Public\PublicController::class, 'team'])->name('team');

    // Testimonials
    Route::get('/testimonials', [App\Http\Controllers\Public\PublicController::class, 'testimonials'])->name('testimonials');
    Route::post('/testimonials', [App\Http\Controllers\Public\PublicController::class, 'storeTestimonial'])->name('testimonials.store');

    // Contact
    Route::get('/contact', [App\Http\Controllers\Public\PublicController::class, 'contact'])->name('contact');
    Route::post('/contact', [App\Http\Controllers\Public\PublicController::class, 'sendContact'])->name('contact.send');

    // Blog and articles
    Route::get('/blog', [App\Http\Controllers\Public\PublicController::class, 'blog'])->name('blog');
    Route::get('/blog/{slug}', [App\Http\Controllers\Public\PublicController::class, 'blogDetail'])->name('blog.detail');

    // Additional public pages
    Route::get('/timeline', [App\Http\Controllers\Public\PublicController::class, 'timeline'])->name('timeline');
    Route::get('/faq', [App\Http\Controllers\Public\PublicController::class, 'faq'])->name('faq');
    Route::get('/privacy', [App\Http\Controllers\Public\PublicController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [App\Http\Controllers\Public\PublicController::class, 'terms'])->name('terms');

});

// Leaderboard (outside public group to avoid public. prefix)
Route::middleware('maintenance')->group(function () {
    Route::get('/leaderboard', [App\Http\Controllers\Public\LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/leaderboard/data/{competition}', [App\Http\Controllers\Public\LeaderboardController::class, 'getLeaderboardDataJson'])->name('leaderboard.data');
    Route::get('/leaderboard/export/{competition}', [App\Http\Controllers\Public\LeaderboardController::class, 'exportCsv'])->name('leaderboard.export');
});

// Competition Rounds (matalomba) - following the structure from predecessor website
Route::middleware('maintenance')->prefix('matalomba')->name('matalomba.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\CompetitionRoundController::class, 'index'])->name('index');
    Route::get('/{competition:slug}', [App\Http\Controllers\Public\CompetitionRoundController::class, 'show'])->name('show');
    Route::get('/{competition:slug}/final', [App\Http\Controllers\Public\CompetitionRoundController::class, 'showFinalResults'])->name('final');
    Route::get('/{competition:slug}/{roundType}', [App\Http\Controllers\Public\CompetitionRoundController::class, 'showRound'])->name('round');
    Route::get('/{competition:slug}/{roundType}/{matchName}', [App\Http\Controllers\Public\CompetitionRoundController::class, 'showMatch'])->name('match')->where('matchName', '.*');
});

// Route alias for backward compatibility
Route::get('/home-alias', [App\Http\Controllers\Public\PublicController::class, 'home'])->name('home');

// CSRF Token Refresh Route
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->name('csrf.token');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Auth\AuthController::class, 'showRegister'])->name('register')->middleware('registration.open');
    Route::post('/register', [App\Http\Controllers\Auth\AuthController::class, 'register'])->middleware('registration.open');
    Route::get('/forgot-password', [App\Http\Controllers\Auth\AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\AuthController::class, 'resetPassword'])->name('password.update');
});

// Authenticated Routes
Route::middleware(['auth', 'verified', 'maintenance'])->group(function () {
    
    // Logout
    Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');
    
    // Dashboard - Redirect based on role
    Route::get('/dashboard', function () {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isFinance()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isJuri()) {
            return redirect()->route('juri.juri.dashboard');
        } elseif ($user->isPeserta()) {
            return redirect()->route('peserta.dashboard');
        }

        return redirect()->route('login')
            ->with('error', 'Role tidak dikenali. Silakan hubungi administrator.');
    })->name('dashboard');
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\Auth\AuthController::class, 'profile'])->name('index');
        Route::put('/', [App\Http\Controllers\Auth\AuthController::class, 'updateProfile'])->name('update');
        Route::put('/password', [App\Http\Controllers\Auth\AuthController::class, 'updatePassword'])->name('password');
    });

    // Super Admin & Admin & Finance Routes
    Route::middleware(['role:superadmin|admin|finance'])->prefix('admin')->name('admin.')->group(function () {

        // Competition Categories (Super Admin only)
        Route::middleware(['role:superadmin'])->group(function () {
            Route::resource('competition-categories', App\Http\Controllers\Admin\CompetitionCategoryController::class);
        });

        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/chart-data', [App\Http\Controllers\Admin\DashboardController::class, 'getChartDataAjax'])->name('dashboard.chart-data');
        Route::get('/dashboard/user-distribution', [App\Http\Controllers\Admin\DashboardController::class, 'getUserDistributionAjax'])->name('dashboard.user-distribution');
        Route::get('/dashboard/recent-data', [App\Http\Controllers\Admin\DashboardController::class, 'getRecentDataAjax'])->name('dashboard.recent-data');
        
        // Competition Management
        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CompetitionController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\CompetitionController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\CompetitionController::class, 'store'])->name('store');
            Route::get('/{competition}', [App\Http\Controllers\Admin\CompetitionController::class, 'show'])->name('show');
            Route::get('/{competition}/edit', [App\Http\Controllers\Admin\CompetitionController::class, 'edit'])->name('edit');
            Route::put('/{competition}', [App\Http\Controllers\Admin\CompetitionController::class, 'update'])->name('update');
            Route::delete('/{competition}', [App\Http\Controllers\Admin\CompetitionController::class, 'destroy'])->name('destroy');
            Route::patch('/{competition}/toggle-status', [App\Http\Controllers\Admin\CompetitionController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/{competition}/registrations', [App\Http\Controllers\Admin\CompetitionController::class, 'registrations'])->name('registrations');
            Route::get('/{competition}/export', [App\Http\Controllers\Admin\CompetitionController::class, 'export'])->name('export');

            // Competition Descriptions
            Route::prefix('{competition}/descriptions')->name('descriptions.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\CompetitionDescriptionController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Admin\CompetitionDescriptionController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Admin\CompetitionDescriptionController::class, 'store'])->name('store');
                Route::get('/{description}/edit', [App\Http\Controllers\Admin\CompetitionDescriptionController::class, 'edit'])->name('edit');
                Route::put('/{description}', [App\Http\Controllers\Admin\CompetitionDescriptionController::class, 'update'])->name('update');
                Route::delete('/{description}', [App\Http\Controllers\Admin\CompetitionDescriptionController::class, 'destroy'])->name('destroy');

                // Special route for Terms & Conditions
                Route::put('/terms', [App\Http\Controllers\Admin\CompetitionDescriptionController::class, 'updateTerms'])->name('update-terms');
            });
        });
        
        // Registration Management
        Route::prefix('registrations')->name('registrations.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\RegistrationController::class, 'index'])->name('index');
            Route::get('/{registration}', [App\Http\Controllers\Admin\RegistrationController::class, 'show'])->name('show');
            Route::patch('/{registration}', [App\Http\Controllers\Admin\RegistrationController::class, 'update'])->name('update');
            // DISABLED: Manual registration confirmation feature has been disabled
            // Route::patch('/{registration}/confirm', [App\Http\Controllers\Admin\RegistrationController::class, 'confirm'])->name('confirm');
            Route::patch('/{registration}/cancel', [App\Http\Controllers\Admin\RegistrationController::class, 'cancel'])->name('cancel');
            Route::patch('/{registration}/re-enable', [App\Http\Controllers\Admin\RegistrationController::class, 'reEnable'])->name('re-enable');
            Route::patch('/{registration}/lock', [App\Http\Controllers\Admin\RegistrationController::class, 'lock'])->name('lock');
            Route::patch('/{registration}/unlock', [App\Http\Controllers\Admin\RegistrationController::class, 'unlock'])->name('unlock');
            Route::delete('/{registration}', [App\Http\Controllers\Admin\RegistrationController::class, 'destroy'])->name('destroy');
            Route::get('/export/excel', [App\Http\Controllers\Admin\RegistrationController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [App\Http\Controllers\Admin\RegistrationController::class, 'exportPdf'])->name('export.pdf');
        });
        
        // Payment Management
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('index');
            Route::get('/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('show');
            Route::patch('/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'update'])->name('update');
            Route::patch('/{payment}/verify', [App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('verify');
            Route::patch('/{payment}/reject', [App\Http\Controllers\Admin\PaymentController::class, 'reject'])->name('reject');
            Route::patch('/{payment}/confirm', [App\Http\Controllers\Admin\PaymentController::class, 'confirmPayment'])->name('confirm');
        });
        
        // User Management (Super Admin only)
        Route::middleware(['role:superadmin'])->prefix('users')->name('users.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
            Route::get('/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
            Route::patch('/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Role Management (Super Admin only)
        Route::middleware(['role:superadmin'])->prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\RoleController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\RoleController::class, 'store'])->name('store');
            Route::get('/{role}', [App\Http\Controllers\Admin\RoleController::class, 'show'])->name('show');
            Route::get('/{role}/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('edit');
            Route::put('/{role}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('destroy');
        });
        
        // Submission Management
        Route::prefix('submissions')->name('submissions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SubmissionController::class, 'index'])->name('index');
            Route::get('/export', [App\Http\Controllers\Admin\SubmissionController::class, 'export'])->name('export');
            Route::get('/{submission}', [App\Http\Controllers\Admin\SubmissionController::class, 'show'])->name('show');
            Route::patch('/{submission}/approve', [App\Http\Controllers\Admin\SubmissionController::class, 'approve'])->name('approve');
            Route::patch('/{submission}/reject', [App\Http\Controllers\Admin\SubmissionController::class, 'reject'])->name('reject');
            Route::delete('/{submission}', [App\Http\Controllers\Admin\SubmissionController::class, 'destroy'])->name('destroy');
        });

        // User Activation Management
        Route::prefix('user-activation')->name('user-activation.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\UserActivationController::class, 'index'])->name('index');
            Route::patch('/{user}/activate', [App\Http\Controllers\Admin\UserActivationController::class, 'activate'])->name('activate');
            Route::patch('/{user}/deactivate', [App\Http\Controllers\Admin\UserActivationController::class, 'deactivate'])->name('deactivate');
            Route::post('/bulk-activate', [App\Http\Controllers\Admin\UserActivationController::class, 'bulkActivate'])->name('bulk-activate');
        });

        // Judge Assignment Management
        Route::prefix('judge-assignment')->name('judge-assignment.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\JudgeAssignmentController::class, 'index'])->name('index');
            Route::post('/auto-assign', [App\Http\Controllers\Admin\JudgeAssignmentController::class, 'autoAssign'])->name('auto-assign');
            Route::post('/assign', [App\Http\Controllers\Admin\JudgeAssignmentController::class, 'assign'])->name('assign');
            Route::post('/unassign', [App\Http\Controllers\Admin\JudgeAssignmentController::class, 'unassign'])->name('unassign');
            Route::get('/workload', [App\Http\Controllers\Admin\JudgeAssignmentController::class, 'getWorkload'])->name('workload');
        });

        // Certificate Management
        Route::prefix('certificates')->name('certificates.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CertificateController::class, 'index'])->name('index');
            Route::post('/{registration}/generate-winner', [App\Http\Controllers\Admin\CertificateController::class, 'generateWinner'])->name('generate-winner');
            Route::get('/{registration}/generate-participation', [App\Http\Controllers\Admin\CertificateController::class, 'generateParticipation'])->name('generate-participation');
            Route::post('/generate-bulk', [App\Http\Controllers\Admin\CertificateController::class, 'generateBulk'])->name('generate-bulk');
            Route::get('/{registration}/preview', [App\Http\Controllers\Admin\CertificateController::class, 'preview'])->name('preview');
        });

        // Export Management
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ExportController::class, 'index'])->name('index');
            Route::get('/registrations', [App\Http\Controllers\Admin\ExportController::class, 'exportRegistrations'])->name('registrations');
            Route::get('/payments', [App\Http\Controllers\Admin\ExportController::class, 'exportPayments'])->name('payments');
            Route::get('/submissions', [App\Http\Controllers\Admin\ExportController::class, 'exportSubmissions'])->name('submissions');
            Route::get('/scores', [App\Http\Controllers\Admin\ExportController::class, 'exportScores'])->name('scores');
        });

        // Activity Logs
        Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('index');
            Route::get('/{activityLog}', [App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('show');
            Route::post('/clean', [App\Http\Controllers\Admin\ActivityLogController::class, 'clean'])->name('clean');
            Route::get('/export/csv', [App\Http\Controllers\Admin\ActivityLogController::class, 'export'])->name('export');
        });

        // Global Search
        Route::prefix('search')->name('search.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SearchController::class, 'index'])->name('index');
            Route::get('/ajax', [App\Http\Controllers\Admin\SearchController::class, 'ajax'])->name('ajax');
        });

        // Settings Management
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
            Route::put('/update', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('update');
            Route::post('/toggle-maintenance', [App\Http\Controllers\Admin\SettingsController::class, 'toggleMaintenance'])->name('toggle-maintenance');
            Route::post('/toggle-registration', [App\Http\Controllers\Admin\SettingsController::class, 'toggleRegistration'])->name('toggle-registration');
        });

        // Maintenance Tools
        Route::prefix('maintenance')->name('maintenance.')->group(function () {
            Route::post('/clear-cache', [App\Http\Controllers\Admin\MaintenanceController::class, 'clearCache'])->name('clear-cache');
            Route::post('/optimize', [App\Http\Controllers\Admin\MaintenanceController::class, 'optimize'])->name('optimize');
            Route::post('/clear-logs', [App\Http\Controllers\Admin\MaintenanceController::class, 'clearLogs'])->name('clear-logs');
            Route::post('/run-all', [App\Http\Controllers\Admin\MaintenanceController::class, 'runAll'])->name('run-all');
            Route::get('/health-check', [App\Http\Controllers\Admin\MaintenanceController::class, 'healthCheck'])->name('health-check');
        });



        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
            Route::get('/competitions', [App\Http\Controllers\Admin\ReportController::class, 'competitions'])->name('competitions');
            Route::get('/registrations', [App\Http\Controllers\Admin\ReportController::class, 'registrations'])->name('registrations');
            Route::get('/payments', [App\Http\Controllers\Admin\ReportController::class, 'payments'])->name('payments');
            Route::get('/export/{type}', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('export');
            Route::get('/competition-distribution', [App\Http\Controllers\Admin\ReportController::class, 'getCompetitionDistribution'])->name('competition-distribution');
            Route::get('/registration-trend', [App\Http\Controllers\Admin\ReportController::class, 'getRegistrationTrend'])->name('registration-trend');
            Route::get('/revenue-trend', [App\Http\Controllers\Admin\ReportController::class, 'getRevenueTrend'])->name('revenue-trend');
        });



        // Finance Management
        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/invoices', function() {
                return view('admin.finance.invoices');
            })->name('invoices');
        });


    });

    // Juri Routes
    Route::middleware(['role:juri'])->prefix('juri')->name('juri.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Juri\JuriDashboardController::class, 'index'])->name('juri.dashboard');
        
        // Assigned Competitions
        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Juri\CompetitionController::class, 'index'])->name('index');
            Route::get('/{competition}', [App\Http\Controllers\Juri\CompetitionController::class, 'show'])->name('show');
            Route::get('/{competition}/participants', [App\Http\Controllers\Juri\CompetitionController::class, 'participants'])->name('participants');
        });
        
        // Scoring System
        Route::prefix('scoring')->name('scoring.')->group(function () {
            Route::get('/', [App\Http\Controllers\Juri\ScoringController::class, 'index'])->name('index');
            Route::get('/competition/{competition}', [App\Http\Controllers\Juri\ScoringController::class, 'competition'])->name('competition');
            Route::get('/submission/{submission}', [App\Http\Controllers\Juri\ScoringController::class, 'submission'])->name('submission');
            Route::post('/submission/{submission}', [App\Http\Controllers\Juri\ScoringController::class, 'store'])->name('store');
            Route::get('/participant/{registration}', [App\Http\Controllers\Juri\ScoringController::class, 'participant'])->name('participant');
            Route::post('/score/{registration}', [App\Http\Controllers\Juri\ScoringController::class, 'score'])->name('score');
            Route::put('/score/{score}', [App\Http\Controllers\Juri\ScoringController::class, 'update'])->name('update');
            Route::post('/score/{score}/submit', [App\Http\Controllers\Juri\ScoringController::class, 'submit'])->name('submit');
            Route::post('/finalize/{competition}', [App\Http\Controllers\Juri\ScoringController::class, 'finalize'])->name('finalize');

            // Round-based scoring
            Route::get('/rounds', [App\Http\Controllers\Juri\ScoringController::class, 'rounds'])->name('rounds');
            Route::get('/match/{match}', [App\Http\Controllers\Juri\ScoringController::class, 'scoreMatch'])->name('match');
            Route::post('/match/{match}', [App\Http\Controllers\Juri\ScoringController::class, 'storeMatchScore'])->name('match.store');
            
            // Download submission files
            Route::get('/download/{submission}/{filename}', [App\Http\Controllers\Juri\ScoringController::class, 'downloadFile'])->name('download');
        });
        
        // Submissions Review
        Route::prefix('submissions')->name('submissions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Juri\SubmissionController::class, 'index'])->name('index');
            Route::get('/{submission}', [App\Http\Controllers\Juri\SubmissionController::class, 'show'])->name('show');
            Route::post('/{submission}/comment', [App\Http\Controllers\Juri\SubmissionController::class, 'addComment'])->name('comment');
        });

        // Judging Form with Tabulator
        Route::get('/judging/form', function() {
            return view('juri.judging.form');
        })->name('judging.form');

        // Export & Reports
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/scores', [App\Http\Controllers\Juri\ExportController::class, 'exportScores'])->name('scores');
            Route::get('/detailed-report', [App\Http\Controllers\Juri\ExportController::class, 'exportDetailedReport'])->name('detailed-report');
            Route::get('/statistics', [App\Http\Controllers\Juri\ExportController::class, 'exportStatistics'])->name('statistics');
            Route::get('/pdf-report', [App\Http\Controllers\Juri\ExportController::class, 'generatePDFReport'])->name('pdf-report');
        });
    });

    // Peserta Routes
    Route::middleware(['role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Peserta\PesertaDashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [App\Http\Controllers\Auth\AuthController::class, 'profile'])->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Auth\AuthController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [App\Http\Controllers\Auth\AuthController::class, 'updatePassword'])->name('profile.password');

        // Available Competitions
        Route::prefix('competitions')->name('competitions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Peserta\CompetitionController::class, 'index'])->name('index');
            Route::get('/{competition}', [App\Http\Controllers\Peserta\CompetitionController::class, 'show'])->name('show');
            Route::post('/{competition}/register', [App\Http\Controllers\Peserta\CompetitionController::class, 'register'])->name('register');
        });
        
        // My Registrations
        Route::prefix('registrations')->name('registrations.')->group(function () {
            Route::get('/', [App\Http\Controllers\Peserta\RegistrationController::class, 'index'])->name('index');
            Route::get('/{registration}', [App\Http\Controllers\Peserta\RegistrationController::class, 'show'])->name('show');
            Route::get('/{registration}/documents', [App\Http\Controllers\Peserta\RegistrationController::class, 'documents'])->name('documents');
            Route::put('/{registration}', [App\Http\Controllers\Peserta\RegistrationController::class, 'update'])->name('update');
            Route::delete('/{registration}', [App\Http\Controllers\Peserta\RegistrationController::class, 'cancel'])->name('cancel');
            Route::get('/{registration}/ticket', [App\Http\Controllers\Peserta\RegistrationController::class, 'ticket'])->name('ticket');
            Route::post('/{registration}/refresh-payment', [App\Http\Controllers\Peserta\RegistrationController::class, 'refreshPaymentStatus'])->name('refresh-payment');
        });
        
        // Submissions
        Route::prefix('submissions')->name('submissions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Peserta\SubmissionController::class, 'index'])->name('index');
            Route::get('/create/{registration}', [App\Http\Controllers\Peserta\SubmissionController::class, 'create'])->name('create');
            Route::post('/{registration}', [App\Http\Controllers\Peserta\SubmissionController::class, 'store'])->name('store');
            Route::get('/{submission}', [App\Http\Controllers\Peserta\SubmissionController::class, 'show'])->name('show');
            Route::get('/{submission}/edit', [App\Http\Controllers\Peserta\SubmissionController::class, 'edit'])->name('edit');
            Route::put('/{submission}', [App\Http\Controllers\Peserta\SubmissionController::class, 'update'])->name('update');
            Route::post('/{submission}/submit', [App\Http\Controllers\Peserta\SubmissionController::class, 'submit'])->name('submit');
            Route::post('/{submission}/upload', [App\Http\Controllers\Peserta\SubmissionController::class, 'uploadFile'])->name('upload');
            Route::delete('/{submission}/file/{filename}', [App\Http\Controllers\Peserta\SubmissionController::class, 'deleteFile'])->name('delete-file');
        });

        // Notification Preferences
        Route::prefix('notification-preferences')->name('notification-preferences.')->group(function () {
            Route::get('/', [App\Http\Controllers\Peserta\NotificationPreferenceController::class, 'index'])->name('index');
            Route::put('/', [App\Http\Controllers\Peserta\NotificationPreferenceController::class, 'update'])->name('update');
            Route::post('/reset', [App\Http\Controllers\Peserta\NotificationPreferenceController::class, 'reset'])->name('reset');
        });
    });
});

// Payment Routes (Public for callback)
Route::prefix('payment')->name('payment.')->group(function () {
    
    // Payment process for authenticated users
    Route::middleware(['auth'])->group(function () {
        Route::get('/checkout/{registration}', [PaymentController::class, 'checkout'])->name('checkout');
        Route::post('/process/{registration}', [PaymentController::class, 'process'])->name('process');
        Route::post('/update-method/{registration}', [PaymentController::class, 'updatePaymentMethod'])->name('update-method');
        Route::get('/status/{paymentId}', [PaymentController::class, 'status'])->name('status');
        Route::get('/finish/{payment}', [PaymentController::class, 'finish'])->name('finish')->where('payment', '[0-9]+');
        Route::get('/unfinish/{payment}', [PaymentController::class, 'unfinish'])->name('unfinish')->where('payment', '[0-9]+');
        Route::get('/error/{payment}', [PaymentController::class, 'error'])->name('error')->where('payment', '[0-9]+');
        Route::post('/check-status', [PaymentController::class, 'checkStatus'])->name('check-status');
        Route::get('/receipt/{payment}', [PaymentController::class, 'downloadReceipt'])->name('receipt');
        Route::get('/invoice/{registration}', [PaymentController::class, 'invoice'])->name('invoice');
    });
    
    // Public callback routes for Midtrans
    Route::post('/notification', [PaymentController::class, 'notification'])->name('notification');
});

// Legacy routes for backward compatibility
Route::prefix('public')->group(function () {
    Route::get('/', function () {
        return redirect()->route('public.home');
    });
    Route::get('/competitions', function () {
        return redirect()->route('public.competitions');
    });
    Route::get('/about', function () {
        return redirect()->route('public.about');
    });
    Route::get('/contact', function () {
        return redirect()->route('public.contact');
    });
    Route::get('/testimonials', function () {
        return redirect()->route('public.testimonials');
    });
    Route::get('/blog', function () {
        return redirect()->route('public.blog');
    });
    Route::get('/timeline', function () {
        return redirect()->route('public.timeline');
    });
});

// API Routes for AJAX calls
Route::prefix('api')->name('api.')->middleware(['auth'])->group(function () {
    
    // Competition API
    Route::get('/competitions', [App\Http\Controllers\Api\CompetitionController::class, 'index']);
    Route::get('/competitions/{competition}', [App\Http\Controllers\Api\CompetitionController::class, 'show']);
    
    // Registration API
    Route::get('/registrations', [App\Http\Controllers\Api\RegistrationController::class, 'index']);
    Route::get('/registrations/datatable', [App\Http\Controllers\Api\RegistrationController::class, 'datatable']);
    Route::get('/registrations/{registration}/documents', [App\Http\Controllers\Api\RegistrationController::class, 'documents']);
    Route::post('/registrations/{registration}/documents', [App\Http\Controllers\Api\RegistrationController::class, 'uploadDocument']);
    Route::delete('/registrations/{registration}/documents/{type}', [App\Http\Controllers\Api\RegistrationController::class, 'deleteDocument']);
    
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
});

// File Download Routes (Authenticated users only)
Route::middleware(['auth'])->prefix('download')->name('download.')->group(function () {
    Route::get('/submission/{submission}/{filename}', [App\Http\Controllers\DownloadController::class, 'submission'])->name('submission');
    Route::get('/payment/{payment}/invoice', [App\Http\Controllers\DownloadController::class, 'invoice'])->name('invoice');
    Route::get('/registration/{registration}/ticket', [App\Http\Controllers\DownloadController::class, 'ticket'])->name('ticket');

    // Unified invoice download for all roles except juri
    Route::get('/invoice/{registration}', [App\Http\Controllers\DownloadController::class, 'unifiedInvoice'])->name('unified-invoice');
});

// Error Pages
Route::get('/403', function () {
    return view('errors.403');
})->name('errors.403');

Route::get('/404', function () {
    return view('errors.404');
})->name('errors.404');

Route::get('/500', function () {
    return view('errors.500');
})->name('errors.500');

// Development Tools (only in local/development environment)
if (app()->environment(['local', 'development'])) {
    Route::prefix('dev')->name('dev.')->group(function () {
        Route::get('/', [App\Http\Controllers\DevController::class, 'index'])->name('index');
        Route::post('/reset-payments', [App\Http\Controllers\DevController::class, 'resetPayments'])->name('reset-payments');

        Route::post('/test-payment', [App\Http\Controllers\DevController::class, 'testPayment'])->name('test-payment');
    });
}

// Visitor Logs Routes (Admin only with secret key)
Route::prefix('admin/visitor-logs')->group(function () {
    Route::get('/', [App\Http\Controllers\VisitorLogController::class, 'index'])->name('admin.visitor-logs');
    Route::get('/export', [App\Http\Controllers\VisitorLogController::class, 'export'])->name('admin.visitor-logs.export');
});

// Fallback route for 404
Route::fallback(function () {
    return view('errors.404');
});
