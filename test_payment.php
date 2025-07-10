<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Payment;
use App\Services\MidtransService;
use App\Helpers\MidtransHelper;

echo "=== UNAS Fest 2025 Payment Debug Test ===\n\n";

// Test 1: Check Midtrans Configuration
echo "1. Testing Midtrans Configuration:\n";
echo "   Server Key exists: " . (env('MIDTRANS_SERVER_KEY') ? 'YES' : 'NO') . "\n";
echo "   Client Key exists: " . (env('MIDTRANS_CLIENT_KEY') ? 'YES' : 'NO') . "\n";
echo "   Is Production: " . (env('MIDTRANS_IS_PRODUCTION') ? 'YES' : 'NO') . "\n";
echo "   Helper isConfigured: " . (MidtransHelper::isConfigured() ? 'YES' : 'NO') . "\n\n";

// Test 2: Check Database Connection
echo "2. Testing Database Connection:\n";
try {
    $userCount = User::count();
    echo "   Users in database: $userCount\n";
    
    $competitionCount = Competition::where('is_active', true)->count();
    echo "   Active competitions: $competitionCount\n";
    
    $registrationCount = Registration::count();
    echo "   Total registrations: $registrationCount\n";
    
    $paymentCount = Payment::count();
    echo "   Total payments: $paymentCount\n";
} catch (Exception $e) {
    echo "   Database Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 3: Test MidtransService
echo "3. Testing MidtransService:\n";
try {
    $midtransService = new MidtransService();
    echo "   Service created: YES\n";
    echo "   Service isConfigured: " . ($midtransService->isConfigured() ? 'YES' : 'NO') . "\n";
} catch (Exception $e) {
    echo "   Service Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 4: Find a test user and competition
echo "4. Finding Test Data:\n";
try {
    $testUser = User::where('email', 'like', 'peserta%@unasfest.com')->first();
    if ($testUser) {
        echo "   Test User Found: {$testUser->name} ({$testUser->email})\n";
    } else {
        echo "   No test user found\n";
    }
    
    $testCompetition = Competition::where('is_active', true)->first();
    if ($testCompetition) {
        echo "   Test Competition Found: {$testCompetition->name}\n";
        echo "   Competition Price: Rp " . number_format($testCompetition->price) . "\n";
    } else {
        echo "   No active competition found\n";
    }
} catch (Exception $e) {
    echo "   Error finding test data: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 5: Test Registration Creation (if we have test data)
if (isset($testUser) && isset($testCompetition) && $testUser && $testCompetition) {
    echo "5. Testing Registration Creation:\n";
    try {
        // Check if registration already exists
        $existingRegistration = Registration::where('user_id', $testUser->id)
            ->where('competition_id', $testCompetition->id)
            ->first();
            
        if ($existingRegistration) {
            echo "   Existing registration found: ID {$existingRegistration->id}\n";
            $testRegistration = $existingRegistration;
        } else {
            // Create test registration
            $testRegistration = new Registration();
            $testRegistration->user_id = $testUser->id;
            $testRegistration->competition_id = $testCompetition->id;
            $testRegistration->registration_number = 'TEST-' . time();
            $testRegistration->team_name = 'Test Team Payment';
            $testRegistration->institution = 'Test Institution';
            $testRegistration->phone = '081234567890';
            $testRegistration->participant_category = 'external_student';
            $testRegistration->amount = $testCompetition->price;
            $testRegistration->status = 'pending';
            $testRegistration->registered_at = now();
            
            $testRegistration->save();
            echo "   Test registration created: ID {$testRegistration->id}\n";
        }
        
        echo "   Registration Amount: Rp " . number_format($testRegistration->amount) . "\n";
    } catch (Exception $e) {
        echo "   Registration Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // Test 6: Test Payment Creation
    if (isset($testRegistration) && $testRegistration) {
        echo "6. Testing Payment Creation:\n";
        try {
            $midtransService = new MidtransService();
            $result = $midtransService->createTransaction($testRegistration);
            
            if ($result['success']) {
                echo "   Payment creation: SUCCESS\n";
                echo "   Snap Token: " . substr($result['snap_token'], 0, 20) . "...\n";
                echo "   Payment ID: {$result['payment_id']}\n";
            } else {
                echo "   Payment creation: FAILED\n";
                echo "   Error: {$result['message']}\n";
            }
        } catch (Exception $e) {
            echo "   Payment Error: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
}

// Test 7: Test Payment Routes
echo "7. Testing Payment Routes:\n";
try {
    // Test checkout route
    $checkoutUrl = route('payment.checkout', ['registration' => $testRegistration->id]);
    echo "   Checkout URL: $checkoutUrl\n";

    // Test process route
    $processUrl = route('payment.process', ['registration' => $testRegistration->id]);
    echo "   Process URL: $processUrl\n";

    // Check if payment was created
    $payment = Payment::where('registration_id', $testRegistration->id)->first();
    if ($payment) {
        echo "   Payment Record: ID {$payment->id}, Status: {$payment->transaction_status}\n";
        echo "   Snap Token: " . ($payment->snap_token ? 'EXISTS' : 'MISSING') . "\n";

        // Test status route
        $statusUrl = route('payment.status', ['payment' => $payment->id]);
        echo "   Status URL: $statusUrl\n";
    }
} catch (Exception $e) {
    echo "   Route Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 8: Check for common issues
echo "8. Checking Common Issues:\n";
try {
    // Check if registration has proper amount
    if ($testRegistration->amount <= 0) {
        echo "   ❌ Registration amount is zero or negative\n";
    } else {
        echo "   ✅ Registration amount is valid: Rp " . number_format($testRegistration->amount) . "\n";
    }

    // Check if user has proper role
    if ($testUser->hasRole('peserta')) {
        echo "   ✅ User has peserta role\n";
    } else {
        echo "   ❌ User does not have peserta role\n";
    }

    // Check if competition is active
    if ($testCompetition->is_active) {
        echo "   ✅ Competition is active\n";
    } else {
        echo "   ❌ Competition is not active\n";
    }

    // Check registration status
    echo "   Registration Status: {$testRegistration->status}\n";

} catch (Exception $e) {
    echo "   Check Error: " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== Test Complete ===\n";
echo "\nIf payment is not working in browser, check:\n";
echo "1. Login as peserta1@unasfest.com (password: password123)\n";
echo "2. Go to competitions page and register for a competition\n";
echo "3. Check browser console for JavaScript errors\n";
echo "4. Check if Snap popup is blocked by browser\n";
echo "5. Verify CSRF token is present in the page\n";
