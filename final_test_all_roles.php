<?php

// Final comprehensive test for all user roles and login flows
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== UNAS Fest 2025 Final Complete Test ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "Testing all user roles and login flows\n\n";

// Test users with corrected passwords
$testUsers = [
    [
        'email' => 'superadmin@unasfest.com',
        'password' => 'password123', // Corrected password
        'role' => 'superadmin',
        'expected_redirect' => '/admin',
        'dashboard_name' => 'Super Admin Dashboard'
    ],
    [
        'email' => 'admin@test.com',
        'password' => 'password123',
        'role' => 'admin',
        'expected_redirect' => '/admin',
        'dashboard_name' => 'Admin Dashboard'
    ],
    [
        'email' => 'juri@test.com',
        'password' => 'password123',
        'role' => 'juri',
        'expected_redirect' => '/juri',
        'dashboard_name' => 'Juri Dashboard'
    ],
    [
        'email' => 'peserta@test.com',
        'password' => 'password123',
        'role' => 'peserta',
        'expected_redirect' => '/peserta',
        'dashboard_name' => 'Peserta Dashboard'
    ]
];

$results = [];

foreach ($testUsers as $index => $user) {
    echo "🔐 Testing {$user['role']}: {$user['email']}\n";
    
    // Step 1: Get login page
    $getRequest = Illuminate\Http\Request::create('/login', 'GET');
    $getResponse = $kernel->handle($getRequest);
    
    if ($getResponse->getStatusCode() !== 200) {
        echo "   ❌ Failed to load login page (Status: {$getResponse->getStatusCode()})\n";
        $results[$user['role']] = 'FAILED - Cannot load login page';
        continue;
    }
    
    // Step 2: Extract CSRF token
    $content = $getResponse->getContent();
    preg_match('/name="_token" value="([^"]+)"/', $content, $matches);
    $csrfToken = $matches[1] ?? null;
    
    if (!$csrfToken) {
        echo "   ❌ Failed to extract CSRF token\n";
        $results[$user['role']] = 'FAILED - No CSRF token';
        continue;
    }
    
    echo "   ✅ CSRF token extracted\n";
    
    // Step 3: Attempt login
    $loginRequest = Illuminate\Http\Request::create('/login', 'POST', [
        '_token' => $csrfToken,
        'email' => $user['email'],
        'password' => $user['password'],
        'remember' => false
    ]);
    
    $loginRequest->setLaravelSession($getRequest->session());
    
    try {
        $loginResponse = $kernel->handle($loginRequest);
        $status = $loginResponse->getStatusCode();
        
        if ($status === 302) {
            $location = $loginResponse->headers->get('Location');
            echo "   ✅ Login response: Redirect to {$location}\n";
            
            if (str_contains($location, $user['expected_redirect'])) {
                echo "   ✅ SUCCESS: Correct dashboard redirect\n";
                $results[$user['role']] = 'SUCCESS - Login working correctly';
            } elseif (str_contains($location, '/login')) {
                echo "   ❌ FAILED: Redirected back to login (authentication failed)\n";
                $results[$user['role']] = 'FAILED - Authentication failed';
            } else {
                echo "   ⚠️  WARNING: Unexpected redirect location\n";
                $results[$user['role']] = 'WARNING - Unexpected redirect';
            }
        } elseif ($status === 419) {
            echo "   ❌ FAILED: CSRF Token Mismatch Error (419)\n";
            $results[$user['role']] = 'FAILED - CSRF error';
        } else {
            echo "   ❌ FAILED: Unexpected response status: {$status}\n";
            $results[$user['role']] = "FAILED - Status {$status}";
        }
        
    } catch (Exception $e) {
        echo "   ❌ FAILED: Exception - " . $e->getMessage() . "\n";
        $results[$user['role']] = 'FAILED - Exception: ' . $e->getMessage();
    }
    
    echo "\n";
}

// Test public pages
echo "🌐 Testing Public Pages:\n";
$publicPages = [
    '/' => 'Home Page',
    '/competitions' => 'Competitions List',
    '/about' => 'About Page',
    '/contact' => 'Contact Page'
];

$publicResults = [];
foreach ($publicPages as $url => $name) {
    $request = Illuminate\Http\Request::create($url, 'GET');
    try {
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        if ($status === 200) {
            echo "   ✅ {$name}: Working correctly\n";
            $publicResults[$name] = 'SUCCESS';
        } else {
            echo "   ❌ {$name}: Status {$status}\n";
            $publicResults[$name] = "FAILED - Status {$status}";
        }
    } catch (Exception $e) {
        echo "   ❌ {$name}: Error - " . $e->getMessage() . "\n";
        $publicResults[$name] = 'FAILED - Exception';
    }
}

// Final Summary
echo "\n" . str_repeat("=", 60) . "\n";
echo "📊 FINAL TEST RESULTS SUMMARY\n";
echo str_repeat("=", 60) . "\n";

echo "\n🔐 USER AUTHENTICATION RESULTS:\n";
foreach ($results as $role => $result) {
    $icon = str_contains($result, 'SUCCESS') ? '✅' : (str_contains($result, 'WARNING') ? '⚠️' : '❌');
    echo "   {$icon} " . strtoupper($role) . ": {$result}\n";
}

echo "\n🌐 PUBLIC PAGES RESULTS:\n";
foreach ($publicResults as $page => $result) {
    $icon = str_contains($result, 'SUCCESS') ? '✅' : '❌';
    echo "   {$icon} {$page}: {$result}\n";
}

// Overall Status
$successCount = count(array_filter($results, function($r) { return str_contains($r, 'SUCCESS'); }));
$totalTests = count($results);
$publicSuccessCount = count(array_filter($publicResults, function($r) { return str_contains($r, 'SUCCESS'); }));
$totalPublicTests = count($publicResults);

echo "\n📈 OVERALL STATUS:\n";
echo "   Authentication Tests: {$successCount}/{$totalTests} passed\n";
echo "   Public Pages Tests: {$publicSuccessCount}/{$totalPublicTests} passed\n";

if ($successCount === $totalTests && $publicSuccessCount === $totalPublicTests) {
    echo "   🎉 ALL TESTS PASSED! System is fully operational.\n";
} else {
    echo "   ⚠️  Some tests failed. Please review the results above.\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Test completed at: " . date('Y-m-d H:i:s') . "\n";
echo "UNAS Fest 2025 system status verified.\n";
