<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUGGING PAYMENT ISSUE ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

// Test 1: Check existing payments
echo "1. Checking existing payments...\n";
try {
    $payments = App\Models\Payment::orderBy('created_at', 'desc')->limit(5)->get();
    
    foreach ($payments as $payment) {
        echo "   Payment ID: {$payment->id}\n";
        echo "   Order ID: {$payment->order_id}\n";
        echo "   Status: {$payment->transaction_status}\n";
        echo "   Amount: Rp " . number_format($payment->gross_amount, 0, ',', '.') . "\n";
        echo "   Created: {$payment->created_at}\n";
        echo "   Expired: {$payment->expired_at}\n";
        echo "   ---\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test 2: Test Midtrans API connection
echo "\n2. Testing Midtrans API connection...\n";
try {
    // Set Midtrans configuration
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');
    \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
    \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    
    echo "   Server Key: " . substr(config('midtrans.server_key'), 0, 10) . "...\n";
    echo "   Environment: " . (config('midtrans.is_production') ? 'Production' : 'Sandbox') . "\n";
    
    // Test with a recent payment
    $recentPayment = App\Models\Payment::orderBy('created_at', 'desc')->first();
    if ($recentPayment) {
        echo "   Testing with Order ID: {$recentPayment->order_id}\n";
        
        try {
            $status = \Midtrans\Transaction::status($recentPayment->order_id);
            echo "   ✅ API Connection OK\n";
            echo "   Transaction Status: {$status->transaction_status}\n";
            echo "   Fraud Status: {$status->fraud_status}\n";
        } catch (\Midtrans\Exceptions\MidtransException $e) {
            echo "   ❌ Midtrans API Error: " . $e->getMessage() . "\n";
            echo "   HTTP Code: " . $e->getHttpStatusCode() . "\n";
        }
    } else {
        echo "   ⚠️  No payments found to test\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test 3: Create fresh transaction
echo "\n3. Creating fresh transaction...\n";
try {
    $registration = App\Models\Registration::find(3);
    if ($registration) {
        echo "   Registration: {$registration->id}\n";
        echo "   Amount: Rp " . number_format($registration->amount, 0, ',', '.') . "\n";
        
        // Clean up old payments for this registration
        $oldPayments = App\Models\Payment::where('registration_id', $registration->id)
            ->whereNotIn('transaction_status', ['settlement', 'capture'])
            ->get();
            
        echo "   Found {$oldPayments->count()} old pending payments\n";
        
        foreach ($oldPayments as $oldPayment) {
            echo "   Deleting old payment: {$oldPayment->order_id}\n";
            $oldPayment->delete();
        }
        
        // Create new transaction
        $midtransService = new App\Services\MidtransService();
        $result = $midtransService->createTransaction($registration);
        
        if ($result['success']) {
            echo "   ✅ New transaction created successfully\n";
            echo "   Payment ID: {$result['payment_id']}\n";
            echo "   Order ID: {$result['order_id']}\n";
            echo "   Snap Token: " . substr($result['snap_token'], 0, 20) . "...\n";
            
            // Test the new transaction status
            try {
                $status = \Midtrans\Transaction::status($result['order_id']);
                echo "   ✅ New transaction status check OK\n";
                echo "   Status: {$status->transaction_status}\n";
            } catch (\Midtrans\Exceptions\MidtransException $e) {
                echo "   ❌ New transaction status check failed: " . $e->getMessage() . "\n";
            }
            
        } else {
            echo "   ❌ Transaction creation failed: {$result['message']}\n";
        }
    } else {
        echo "   ❌ Registration not found\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test 4: Check Snap JS loading
echo "\n4. Testing Snap JS configuration...\n";
try {
    $clientKey = config('midtrans.client_key');
    $snapUrl = config('midtrans.is_production') 
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
        
    echo "   Client Key: " . substr($clientKey, 0, 10) . "...\n";
    echo "   Snap URL: {$snapUrl}\n";
    
    // Test if URL is accessible
    $headers = @get_headers($snapUrl);
    if ($headers && strpos($headers[0], '200') !== false) {
        echo "   ✅ Snap JS URL accessible\n";
    } else {
        echo "   ❌ Snap JS URL not accessible\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🔍 PAYMENT DEBUG SUMMARY\n";
echo str_repeat("=", 50) . "\n";

echo "1. Check browser console for JavaScript errors\n";
echo "2. Ensure Midtrans Snap JS is loaded properly\n";
echo "3. Verify order_id uniqueness and format\n";
echo "4. Check Midtrans API connectivity\n";
echo "5. Clear old pending payments before creating new ones\n";

echo "\nDebug completed at: " . date('Y-m-d H:i:s') . "\n";
echo str_repeat("=", 50) . "\n";
