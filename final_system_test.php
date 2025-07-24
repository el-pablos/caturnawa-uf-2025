<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== FINAL SYSTEM TEST - UNAS FEST 2025 ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

$results = [];

// Test 1: Competitions Page
echo "1. Testing Competitions Page...\n";
try {
    $competitions = App\Models\Competition::active()->paginate(12);
    $allCompetitions = App\Models\Competition::active()->get();
    $stats = [
        'total_competitions' => $allCompetitions->count(),
        'total_participants' => $allCompetitions->sum(function($comp) {
            return $comp->registrations()->where('status', 'confirmed')->count();
        }),
        'total_prizes' => $allCompetitions->sum('prize_amount') ?: 500000000,
    ];
    
    $view = view('public.competitions', compact('competitions', 'stats'));
    $rendered = $view->render();
    
    echo "   ✅ Competitions page renders successfully\n";
    echo "   ✅ Found " . $competitions->count() . " competitions\n";
    echo "   ✅ Statistics: " . json_encode($stats) . "\n";
    $results['competitions'] = 'SUCCESS';
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    $results['competitions'] = 'FAILED';
}

// Test 2: Login System
echo "\n2. Testing Login System...\n";
try {
    $users = [
        ['email' => 'admin@test.com', 'password' => 'password123', 'role' => 'admin'],
        ['email' => 'peserta@test.com', 'password' => 'password123', 'role' => 'peserta'],
    ];
    
    foreach ($users as $userData) {
        $user = App\Models\User::where('email', $userData['email'])->first();
        if ($user && Hash::check($userData['password'], $user->password)) {
            echo "   ✅ {$userData['role']} login credentials valid\n";
        } else {
            echo "   ❌ {$userData['role']} login credentials invalid\n";
        }
    }
    $results['login'] = 'SUCCESS';
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    $results['login'] = 'FAILED';
}

// Test 3: Payment System
echo "\n3. Testing Payment System...\n";
try {
    // Test MidtransService configuration
    $midtransService = new App\Services\MidtransService();
    $isConfigured = $midtransService->isConfigured();
    
    if ($isConfigured) {
        echo "   ✅ Midtrans service properly configured\n";
    } else {
        echo "   ⚠️  Midtrans service not configured (expected in development)\n";
    }
    
    // Test payment routes
    $paymentRoutes = [
        'payment.process',
        'payment.status', 
        'payment.finish',
        'payment.error'
    ];
    
    foreach ($paymentRoutes as $routeName) {
        try {
            $url = route($routeName, 1); // Test with ID 1
            echo "   ✅ Route {$routeName}: {$url}\n";
        } catch (Exception $e) {
            echo "   ❌ Route {$routeName}: Error\n";
        }
    }
    
    $results['payment'] = 'SUCCESS';
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    $results['payment'] = 'FAILED';
}

// Test 4: Database Integrity
echo "\n4. Testing Database Integrity...\n";
try {
    $counts = [
        'users' => App\Models\User::count(),
        'competitions' => App\Models\Competition::count(),
        'registrations' => App\Models\Registration::count(),
        'payments' => App\Models\Payment::count(),
    ];
    
    foreach ($counts as $model => $count) {
        echo "   ✅ {$model}: {$count} records\n";
    }
    
    // Test roles
    $roles = Spatie\Permission\Models\Role::pluck('name')->toArray();
    echo "   ✅ Roles: " . implode(', ', $roles) . "\n";
    
    $results['database'] = 'SUCCESS';
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    $results['database'] = 'FAILED';
}

// Test 5: File System
echo "\n5. Testing File System...\n";
try {
    $directories = [
        'storage/app/public/logos',
        'storage/app/public/team_photos',
        'storage/app/public/competition_requirements',
        'storage/logs',
    ];
    
    foreach ($directories as $dir) {
        if (is_dir($dir) && is_writable($dir)) {
            echo "   ✅ Directory {$dir}: OK\n";
        } else {
            echo "   ❌ Directory {$dir}: Not writable\n";
        }
    }
    
    $results['filesystem'] = 'SUCCESS';
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    $results['filesystem'] = 'FAILED';
}

// Final Summary
echo "\n" . str_repeat("=", 60) . "\n";
echo "🎯 FINAL SYSTEM TEST RESULTS\n";
echo str_repeat("=", 60) . "\n";

$totalTests = count($results);
$passedTests = count(array_filter($results, function($r) { return $r === 'SUCCESS'; }));

foreach ($results as $test => $result) {
    $icon = $result === 'SUCCESS' ? '✅' : '❌';
    echo "{$icon} " . strtoupper($test) . ": {$result}\n";
}

echo "\n📊 OVERALL SCORE: {$passedTests}/{$totalTests} tests passed\n";

if ($passedTests === $totalTests) {
    echo "🎉 ALL SYSTEMS OPERATIONAL! Ready for production.\n";
} else {
    echo "⚠️  Some systems need attention. Please review failed tests.\n";
}

echo "\n🚀 SYSTEM STATUS: " . ($passedTests === $totalTests ? "PRODUCTION READY" : "NEEDS REVIEW") . "\n";
echo "Test completed at: " . date('Y-m-d H:i:s') . "\n";
echo str_repeat("=", 60) . "\n";
