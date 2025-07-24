<?php

// Test login functionality
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test 1: Get login page and extract CSRF token
echo "=== Testing Login Flow ===\n";

// Simulate getting login page
$request = Illuminate\Http\Request::create('/login', 'GET');
$response = $kernel->handle($request);

echo "1. GET /login - Status: " . $response->getStatusCode() . "\n";

// Extract CSRF token from response
$content = $response->getContent();
preg_match('/name="_token" value="([^"]+)"/', $content, $matches);
$csrfToken = $matches[1] ?? null;

echo "2. CSRF Token extracted: " . ($csrfToken ? 'Yes' : 'No') . "\n";
echo "   Token: " . substr($csrfToken, 0, 10) . "...\n";

// Test 2: Simulate login POST request
if ($csrfToken) {
    $loginRequest = Illuminate\Http\Request::create('/login', 'POST', [
        '_token' => $csrfToken,
        'email' => 'peserta@test.com',
        'password' => 'password123',
        'remember' => false
    ]);
    
    // Copy session from GET request
    $loginRequest->setLaravelSession($request->session());
    
    try {
        $loginResponse = $kernel->handle($loginRequest);
        echo "3. POST /login - Status: " . $loginResponse->getStatusCode() . "\n";
        
        if ($loginResponse->getStatusCode() === 302) {
            echo "   Redirect to: " . $loginResponse->headers->get('Location') . "\n";
        } else {
            echo "   Response content preview: " . substr($loginResponse->getContent(), 0, 200) . "...\n";
        }
        
    } catch (Exception $e) {
        echo "3. POST /login - Error: " . $e->getMessage() . "\n";
        echo "   Exception type: " . get_class($e) . "\n";
    }
}

// Test 3: Check session configuration
echo "\n=== Session Configuration ===\n";
echo "Driver: " . config('session.driver') . "\n";
echo "Lifetime: " . config('session.lifetime') . " minutes\n";
echo "Cookie: " . config('session.cookie') . "\n";
echo "Domain: " . config('session.domain') . "\n";
echo "Secure: " . (config('session.secure') ? 'Yes' : 'No') . "\n";
echo "HTTP Only: " . (config('session.http_only') ? 'Yes' : 'No') . "\n";
echo "Same Site: " . config('session.same_site') . "\n";

// Test 4: Check database connection
echo "\n=== Database Connection ===\n";
try {
    $pdo = DB::connection()->getPdo();
    echo "Database: Connected\n";
    
    // Check sessions table
    $sessionCount = DB::table('sessions')->count();
    echo "Sessions in DB: " . $sessionCount . "\n";
    
} catch (Exception $e) {
    echo "Database: Error - " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
