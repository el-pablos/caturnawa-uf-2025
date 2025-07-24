<?php

// Test complete login flow for all roles
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== UNAS Fest 2025 Complete Flow Test ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

// Test users with their expected dashboard routes
$testUsers = [
    [
        'email' => 'superadmin@unasfest.com',
        'password' => 'superadmin123',
        'role' => 'superadmin',
        'expected_redirect' => '/admin'
    ],
    [
        'email' => 'admin@test.com',
        'password' => 'password123',
        'role' => 'admin',
        'expected_redirect' => '/admin'
    ],
    [
        'email' => 'juri@test.com',
        'password' => 'password123',
        'role' => 'juri',
        'expected_redirect' => '/juri'
    ],
    [
        'email' => 'peserta@test.com',
        'password' => 'password123',
        'role' => 'peserta',
        'expected_redirect' => '/peserta'
    ]
];

foreach ($testUsers as $index => $user) {
    echo ($index + 1) . ". Testing login for {$user['role']}: {$user['email']}\n";
    
    // Get login page
    $getRequest = Illuminate\Http\Request::create('/login', 'GET');
    $getResponse = $kernel->handle($getRequest);
    
    if ($getResponse->getStatusCode() !== 200) {
        echo "   ✗ Failed to load login page (Status: {$getResponse->getStatusCode()})\n";
        continue;
    }
    
    // Extract CSRF token
    $content = $getResponse->getContent();
    preg_match('/name="_token" value="([^"]+)"/', $content, $matches);
    $csrfToken = $matches[1] ?? null;
    
    if (!$csrfToken) {
        echo "   ✗ Failed to extract CSRF token\n";
        continue;
    }
    
    // Attempt login
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
            echo "   Status: {$status} - Redirect to: {$location}\n";
            
            if (str_contains($location, $user['expected_redirect'])) {
                echo "   ✓ Login successful - correct dashboard redirect\n";
            } elseif (str_contains($location, '/login')) {
                echo "   ✗ Login failed - redirected back to login\n";
            } else {
                echo "   ? Unexpected redirect location\n";
            }
        } elseif ($status === 419) {
            echo "   ✗ CSRF Token Mismatch Error (419)\n";
        } else {
            echo "   ✗ Unexpected response status: {$status}\n";
        }
        
    } catch (Exception $e) {
        echo "   ✗ Login error: " . $e->getMessage() . "\n";
        echo "   Exception: " . get_class($e) . "\n";
    }
    
    echo "\n";
}

// Test public pages
echo "Testing public pages:\n";
$publicPages = [
    '/' => 'Home',
    '/competitions' => 'Competitions',
    '/about' => 'About',
    '/contact' => 'Contact'
];

foreach ($publicPages as $url => $name) {
    $request = Illuminate\Http\Request::create($url, 'GET');
    try {
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        echo "   {$name} ({$url}): Status {$status} " . ($status === 200 ? '✓' : '✗') . "\n";
    } catch (Exception $e) {
        echo "   {$name} ({$url}): Error - " . $e->getMessage() . "\n";
    }
}

echo "\n=== Test Complete ===\n";
