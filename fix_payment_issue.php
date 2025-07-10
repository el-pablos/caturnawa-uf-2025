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
use Illuminate\Support\Facades\DB;

echo "=== UNAS Fest 2025 Payment Issue Fix ===\n\n";

// Step 1: Verify Midtrans Configuration
echo "1. Verifying Midtrans Configuration:\n";
$serverKey = env('MIDTRANS_SERVER_KEY');
$clientKey = env('MIDTRANS_CLIENT_KEY');
$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

echo "   Server Key: " . ($serverKey ? 'CONFIGURED' : 'MISSING') . "\n";
echo "   Client Key: " . ($clientKey ? 'CONFIGURED' : 'MISSING') . "\n";
echo "   Environment: " . ($isProduction ? 'PRODUCTION' : 'SANDBOX') . "\n";
echo "   Helper Status: " . (MidtransHelper::isConfigured() ? 'OK' : 'ERROR') . "\n";

if (!$serverKey || !$clientKey) {
    echo "   ❌ Midtrans not properly configured!\n";
    exit(1);
}
echo "   ✅ Midtrans configuration OK\n\n";

// Step 2: Check Database and Test Data
echo "2. Checking Database and Test Data:\n";
try {
    $testUser = User::where('email', 'peserta1@unasfest.com')->first();
    if (!$testUser) {
        echo "   ❌ Test user not found\n";
        exit(1);
    }
    echo "   ✅ Test user found: {$testUser->name}\n";
    
    $activeCompetitions = Competition::where('is_active', true)->count();
    echo "   ✅ Active competitions: $activeCompetitions\n";
    
    if ($activeCompetitions == 0) {
        echo "   ❌ No active competitions found\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 3: Clean up any problematic payment records
echo "\n3. Cleaning up problematic payment records:\n";
try {
    // Find payments with missing snap tokens
    $problematicPayments = Payment::whereNull('snap_token')->orWhere('snap_token', '')->get();
    echo "   Found {$problematicPayments->count()} payments without snap tokens\n";
    
    foreach ($problematicPayments as $payment) {
        echo "   Deleting payment ID {$payment->id}...\n";
        $payment->delete();
    }
    
    // Find registrations without payments that should have them
    $pendingRegistrations = Registration::where('status', 'pending')
        ->whereDoesntHave('payment')
        ->get();
    echo "   Found {$pendingRegistrations->count()} pending registrations without payments\n";
    
} catch (Exception $e) {
    echo "   ❌ Cleanup error: " . $e->getMessage() . "\n";
}

// Step 4: Test Payment Creation
echo "\n4. Testing Payment Creation:\n";
try {
    $testRegistration = Registration::where('user_id', $testUser->id)->first();
    
    if (!$testRegistration) {
        // Create a test registration
        $competition = Competition::where('is_active', true)->first();
        $testRegistration = new Registration();
        $testRegistration->user_id = $testUser->id;
        $testRegistration->competition_id = $competition->id;
        $testRegistration->registration_number = 'FIX-' . time();
        $testRegistration->team_name = 'Payment Fix Test Team';
        $testRegistration->institution = 'Test Institution';
        $testRegistration->phone = '081234567890';
        $testRegistration->participant_category = 'external_student';
        $testRegistration->amount = $competition->price;
        $testRegistration->status = 'pending';
        $testRegistration->registered_at = now();
        $testRegistration->save();
        
        echo "   ✅ Created test registration ID: {$testRegistration->id}\n";
    } else {
        echo "   ✅ Using existing registration ID: {$testRegistration->id}\n";
    }
    
    // Delete existing payment if any
    Payment::where('registration_id', $testRegistration->id)->delete();
    
    // Test payment creation
    $midtransService = new MidtransService();
    $result = $midtransService->createTransaction($testRegistration);
    
    if ($result['success']) {
        echo "   ✅ Payment creation successful\n";
        echo "   ✅ Snap Token: " . substr($result['snap_token'], 0, 20) . "...\n";
        echo "   ✅ Payment ID: {$result['payment_id']}\n";
    } else {
        echo "   ❌ Payment creation failed: {$result['message']}\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "   ❌ Payment test error: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 5: Generate Test URLs
echo "\n5. Test URLs:\n";
echo "   Login: http://127.0.0.1:8000/login\n";
echo "   Credentials: peserta1@unasfest.com / password123\n";
echo "   Checkout: http://127.0.0.1:8000/payment/checkout/{$testRegistration->id}\n";
echo "   Debug Payment: http://127.0.0.1:8000/debug-payment\n";

echo "\n=== Payment Issue Fix Complete ===\n";
echo "\nThe payment system is now ready for testing.\n";
echo "If you still experience issues, check:\n";
echo "1. Browser console for JavaScript errors\n";
echo "2. Network tab for failed requests\n";
echo "3. Popup blocker settings\n";
echo "4. CSRF token in page source\n";
echo "5. Midtrans Snap script loading\n";
