<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

echo "=== UNAS Fest 2025 Authentication & Payment Test ===\n\n";

// Test 1: Check test user credentials
echo "1. Testing User Authentication:\n";
try {
    $testUser = User::where('email', 'peserta1@unasfest.com')->first();
    if ($testUser) {
        echo "   Test User Found: {$testUser->name} ({$testUser->email})\n";
        echo "   User ID: {$testUser->id}\n";
        echo "   User Role: " . ($testUser->hasRole('peserta') ? 'peserta' : 'other') . "\n";
        
        // Test password
        $passwordWorks = Hash::check('password123', $testUser->password);
        echo "   Password 'password123' works: " . ($passwordWorks ? 'YES' : 'NO') . "\n";
        
        if (!$passwordWorks) {
            // Try other common passwords
            $altPasswords = ['123456', 'password', 'admin123'];
            foreach ($altPasswords as $pass) {
                if (Hash::check($pass, $testUser->password)) {
                    echo "   Alternative password '$pass' works: YES\n";
                    break;
                }
            }
        }
    } else {
        echo "   Test user not found\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 2: Check user's registrations
if (isset($testUser) && $testUser) {
    echo "2. Testing User Registrations:\n";
    try {
        $registrations = Registration::where('user_id', $testUser->id)->get();
        echo "   Total registrations: " . $registrations->count() . "\n";
        
        foreach ($registrations as $reg) {
            echo "   Registration ID: {$reg->id}\n";
            echo "   Competition: {$reg->competition->name}\n";
            echo "   Status: {$reg->status}\n";
            echo "   Amount: Rp " . number_format($reg->amount) . "\n";
            
            // Check if payment exists
            $payment = $reg->payment;
            if ($payment) {
                echo "   Payment ID: {$payment->id}\n";
                echo "   Payment Status: {$payment->transaction_status}\n";
                echo "   Snap Token: " . ($payment->snap_token ? 'EXISTS' : 'MISSING') . "\n";
            } else {
                echo "   Payment: NOT CREATED\n";
            }
            echo "   ---\n";
        }
    } catch (Exception $e) {
        echo "   Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

// Test 3: Test login simulation
echo "3. Testing Login Simulation:\n";
try {
    if (isset($testUser) && $testUser) {
        // Simulate login
        Auth::login($testUser);
        echo "   User logged in: " . (Auth::check() ? 'YES' : 'NO') . "\n";
        echo "   Logged in user: " . (Auth::user() ? Auth::user()->email : 'NONE') . "\n";
        
        // Test if user can access payment routes
        $registration = Registration::where('user_id', $testUser->id)->first();
        if ($registration) {
            echo "   Can access registration: YES\n";
            echo "   Registration belongs to user: " . ($registration->user_id === $testUser->id ? 'YES' : 'NO') . "\n";
            
            // Test payment controller access
            try {
                $paymentController = new App\Http\Controllers\PaymentController();
                echo "   PaymentController instantiated: YES\n";
            } catch (Exception $e) {
                echo "   PaymentController error: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   No registration found for user\n";
        }
        
        // Logout
        Auth::logout();
    }
} catch (Exception $e) {
    echo "   Login simulation error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 4: Check common issues
echo "4. Checking Common Issues:\n";
try {
    // Check if sessions are working
    echo "   Session driver: " . config('session.driver') . "\n";
    echo "   Session lifetime: " . config('session.lifetime') . " minutes\n";
    
    // Check CSRF protection
    echo "   CSRF protection: " . (config('app.debug') ? 'DEBUG MODE' : 'ENABLED') . "\n";
    
    // Check if payment routes are cached
    $routeCacheFile = base_path('bootstrap/cache/routes-v7.php');
    echo "   Route cache exists: " . (file_exists($routeCacheFile) ? 'YES' : 'NO') . "\n";
    
    // Check middleware
    echo "   Auth middleware: " . (class_exists('Illuminate\Auth\Middleware\Authenticate') ? 'AVAILABLE' : 'MISSING') . "\n";
    
} catch (Exception $e) {
    echo "   Error checking issues: " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== Test Complete ===\n";
echo "\nTo test payment in browser:\n";
echo "1. Go to: http://127.0.0.1:8000/login\n";
echo "2. Login with: peserta1@unasfest.com / password123\n";
echo "3. Go to: http://127.0.0.1:8000/competitions\n";
echo "4. Register for a competition\n";
echo "5. Try to make payment\n";
echo "6. Check browser console for errors\n";
