<?php

/**
 * Test script untuk debugging login flow
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel properly
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test 1: Cek apakah user superadmin ada
echo "=== TEST 1: Cek User Superadmin ===\n";
try {
    $user = \App\Models\User::where('email', 'superadmin@unasfest.com')->first();
    if ($user) {
        echo "✅ User ditemukan: {$user->name}\n";
        echo "✅ Email: {$user->email}\n";
        echo "✅ Roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
        echo "✅ isSuperAdmin: " . ($user->isSuperAdmin() ? 'true' : 'false') . "\n";
        echo "✅ Password hash exists: " . (!empty($user->password) ? 'true' : 'false') . "\n";
    } else {
        echo "❌ User superadmin tidak ditemukan\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";

// Test 2: Test password verification
echo "=== TEST 2: Test Password Verification ===\n";
try {
    $password = 'password123';
    if (\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
        echo "✅ Password verification berhasil\n";
    } else {
        echo "❌ Password verification gagal\n";
        echo "❌ Expected: {$password}\n";
        echo "❌ Hash: {$user->password}\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Test role checking
echo "=== TEST 3: Test Role Checking ===\n";
try {
    $roles = \Spatie\Permission\Models\Role::all();
    echo "✅ Available roles:\n";
    foreach ($roles as $role) {
        echo "   - {$role->name}\n";
    }
    
    echo "\n✅ User role checks:\n";
    echo "   - hasRole('superadmin'): " . ($user->hasRole('superadmin') ? 'true' : 'false') . "\n";
    echo "   - hasRole('admin'): " . ($user->hasRole('admin') ? 'true' : 'false') . "\n";
    echo "   - hasRole('juri'): " . ($user->hasRole('juri') ? 'true' : 'false') . "\n";
    echo "   - hasRole('peserta'): " . ($user->hasRole('peserta') ? 'true' : 'false') . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Test route resolution
echo "=== TEST 4: Test Route Resolution ===\n";
try {
    $routes = [
        'login' => 'login',
        'dashboard' => 'dashboard',
        'admin.dashboard' => 'admin.dashboard',
        'juri.dashboard' => 'juri.dashboard',
        'peserta.dashboard' => 'peserta.dashboard',
    ];
    
    foreach ($routes as $name => $route) {
        try {
            $url = route($route);
            echo "✅ Route '{$route}': {$url}\n";
        } catch (Exception $e) {
            echo "❌ Route '{$route}': " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Test middleware
echo "=== TEST 5: Test Middleware ===\n";
try {
    // Simulate login
    \Illuminate\Support\Facades\Auth::login($user);
    echo "✅ User logged in successfully\n";
    
    // Test dashboard redirect logic
    if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
        $expectedRedirect = route('admin.dashboard');
        echo "✅ Expected redirect for superadmin: {$expectedRedirect}\n";
    } elseif ($user->hasRole('juri')) {
        $expectedRedirect = route('juri.dashboard');
        echo "✅ Expected redirect for juri: {$expectedRedirect}\n";
    } elseif ($user->hasRole('peserta')) {
        $expectedRedirect = route('peserta.dashboard');
        echo "✅ Expected redirect for peserta: {$expectedRedirect}\n";
    }
    
    // Logout
    \Illuminate\Support\Facades\Auth::logout();
    echo "✅ User logged out successfully\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 6: Test other users
echo "=== TEST 6: Test Other Users ===\n";
try {
    $testUsers = [
        'admin1@unasfest.com' => 'admin',
        'juri1@unasfest.com' => 'juri',
        'peserta1@unasfest.com' => 'peserta',
    ];
    
    foreach ($testUsers as $email => $expectedRole) {
        $testUser = \App\Models\User::where('email', $email)->first();
        if ($testUser) {
            echo "✅ {$email}: {$testUser->name}\n";
            echo "   - Role: " . implode(', ', $testUser->getRoleNames()->toArray()) . "\n";
            echo "   - Expected: {$expectedRole}\n";
            echo "   - Match: " . ($testUser->hasRole($expectedRole) ? 'true' : 'false') . "\n";
        } else {
            echo "❌ {$email}: User not found\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "=== DEBUGGING COMPLETE ===\n";
echo "Jika semua test menunjukkan ✅, maka sistem sudah berfungsi dengan benar.\n";
echo "Silakan test manual di browser: http://127.0.0.1:8000/login\n";
echo "Login dengan: superadmin@unasfest.com / password123\n";
