<?php

// Test login functionality with improved debugging
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== UNAS Fest 2025 Login Flow Test ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

// Test 1: Check user exists
echo "1. Checking test users...\n";
try {
    $users = DB::table('users')->select('id', 'name', 'email')->get();
    echo "   Total users: " . $users->count() . "\n";
    
    $testUser = DB::table('users')->where('email', 'peserta@test.com')->first();
    if ($testUser) {
        echo "   ✓ Test user found: " . $testUser->name . " (" . $testUser->email . ")\n";
        
        // Check user role
        $userRole = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $testUser->id)
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->first();
            
        if ($userRole) {
            echo "   ✓ User role: " . $userRole->name . "\n";
        } else {
            echo "   ⚠ User has no role assigned\n";
        }
    } else {
        echo "   ✗ Test user not found\n";
    }
} catch (Exception $e) {
    echo "   ✗ Database error: " . $e->getMessage() . "\n";
}

// Test 2: Get login page and extract CSRF token
echo "\n2. Testing GET /login...\n";
$request = Illuminate\Http\Request::create('/login', 'GET');
$response = $kernel->handle($request);

echo "   Status: " . $response->getStatusCode() . "\n";

if ($response->getStatusCode() === 200) {
    $content = $response->getContent();
    preg_match('/name="_token" value="([^"]+)"/', $content, $matches);
    $csrfToken = $matches[1] ?? null;
    
    echo "   ✓ CSRF Token extracted: " . ($csrfToken ? 'Yes' : 'No') . "\n";
    if ($csrfToken) {
        echo "   Token preview: " . substr($csrfToken, 0, 10) . "...\n";
    }
} else {
    echo "   ✗ Failed to load login page\n";
    $csrfToken = null;
}

// Test 3: Simulate login POST request
if ($csrfToken && $testUser) {
    echo "\n3. Testing POST /login...\n";
    
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
        echo "   Status: " . $loginResponse->getStatusCode() . "\n";
        
        if ($loginResponse->getStatusCode() === 302) {
            $location = $loginResponse->headers->get('Location');
            echo "   ✓ Redirect to: " . $location . "\n";
            
            if (str_contains($location, '/peserta')) {
                echo "   ✓ Login successful - redirected to peserta dashboard\n";
            } elseif (str_contains($location, '/login')) {
                echo "   ✗ Login failed - redirected back to login\n";
            } else {
                echo "   ? Unexpected redirect location\n";
            }
        } else {
            echo "   ✗ Unexpected response status\n";
            echo "   Content preview: " . substr($loginResponse->getContent(), 0, 200) . "...\n";
        }
        
    } catch (Exception $e) {
        echo "   ✗ Login error: " . $e->getMessage() . "\n";
        echo "   Exception: " . get_class($e) . "\n";
    }
} else {
    echo "\n3. Skipping login test (missing token or user)\n";
}

// Test 4: Session configuration
echo "\n4. Session Configuration:\n";
echo "   Driver: " . config('session.driver') . "\n";
echo "   Lifetime: " . config('session.lifetime') . " minutes\n";
echo "   Cookie: " . config('session.cookie') . "\n";
echo "   Domain: " . config('session.domain') . "\n";
echo "   Secure: " . (config('session.secure') ? 'Yes' : 'No') . "\n";
echo "   HTTP Only: " . (config('session.http_only') ? 'Yes' : 'No') . "\n";
echo "   Same Site: " . config('session.same_site') . "\n";

echo "\n=== Test Complete ===\n";
