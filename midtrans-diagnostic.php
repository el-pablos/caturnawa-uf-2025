<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== MIDTRANS PAYMENT INTEGRATION DIAGNOSTIC ===\n\n";

// Test 1: Check Environment Variables
echo "TEST 1: Environment Variables Check\n";
echo "===================================\n";

$serverKey = env('MIDTRANS_SERVER_KEY', '');
$clientKey = env('MIDTRANS_CLIENT_KEY', '');
$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
$appUrl = env('APP_URL', '');

echo "✓ MIDTRANS_SERVER_KEY: " . (!empty($serverKey) ? 'SET (' . substr($serverKey, 0, 10) . '...)' : 'NOT SET') . "\n";
echo "✓ MIDTRANS_CLIENT_KEY: " . (!empty($clientKey) ? 'SET (' . substr($clientKey, 0, 10) . '...)' : 'NOT SET') . "\n";
echo "✓ MIDTRANS_IS_PRODUCTION: " . ($isProduction ? 'true (PRODUCTION)' : 'false (SANDBOX)') . "\n";
echo "✓ APP_URL: " . ($appUrl ?: 'NOT SET') . "\n";

if (empty($serverKey) || empty($clientKey)) {
    echo "❌ ERROR: Midtrans keys are not configured!\n";
    echo "   Please set MIDTRANS_SERVER_KEY and MIDTRANS_CLIENT_KEY in .env file\n\n";
} else {
    echo "✅ Midtrans keys are configured\n\n";
}

// Test 2: Check Midtrans Helper
echo "TEST 2: Midtrans Helper Check\n";
echo "=============================\n";

try {
    $configured = \App\Helpers\MidtransHelper::isConfigured();
    $clientKeyHelper = \App\Helpers\MidtransHelper::getClientKey();
    $serverKeyHelper = \App\Helpers\MidtransHelper::getServerKey();
    $snapUrl = \App\Helpers\MidtransHelper::getSnapJsUrl();
    $isProductionHelper = \App\Helpers\MidtransHelper::isProduction();

    echo "✓ MidtransHelper::isConfigured(): " . ($configured ? 'true' : 'false') . "\n";
    echo "✓ MidtransHelper::getClientKey(): " . (!empty($clientKeyHelper) ? 'SET (' . substr($clientKeyHelper, 0, 10) . '...)' : 'EMPTY') . "\n";
    echo "✓ MidtransHelper::getServerKey(): " . (!empty($serverKeyHelper) ? 'SET (' . substr($serverKeyHelper, 0, 10) . '...)' : 'EMPTY') . "\n";
    echo "✓ MidtransHelper::getSnapJsUrl(): " . $snapUrl . "\n";
    echo "✓ MidtransHelper::isProduction(): " . ($isProductionHelper ? 'true' : 'false') . "\n";

    if ($configured) {
        echo "✅ MidtransHelper is working correctly\n\n";
    } else {
        echo "❌ ERROR: MidtransHelper reports not configured\n\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: MidtransHelper failed: " . $e->getMessage() . "\n\n";
}

// Test 3: Check MidtransService
echo "TEST 3: MidtransService Check\n";
echo "=============================\n";

try {
    $midtransService = app(\App\Services\MidtransService::class);
    $serviceConfigured = $midtransService->isConfigured();

    echo "✓ MidtransService instantiated: " . (isset($midtransService) ? 'SUCCESS' : 'FAILED') . "\n";
    echo "✓ MidtransService::isConfigured(): " . ($serviceConfigured ? 'true' : 'false') . "\n";

    if ($serviceConfigured) {
        echo "✅ MidtransService is working correctly\n\n";
    } else {
        echo "❌ ERROR: MidtransService reports not configured\n\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: MidtransService failed: " . $e->getMessage() . "\n\n";
}

// Test 4: Check Application Status
echo "TEST 4: Application Status Check\n";
echo "================================\n";

try {
    $isDown = app()->isDownForMaintenance();
    echo "✓ Maintenance Mode: " . ($isDown ? 'ENABLED (This causes 503 errors!)' : 'DISABLED') . "\n";

    if ($isDown) {
        echo "❌ ERROR: Application is in maintenance mode!\n";
        echo "   Run 'php artisan up' to disable maintenance mode\n\n";
    } else {
        echo "✅ Application is not in maintenance mode\n\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: Could not check maintenance status: " . $e->getMessage() . "\n\n";
}

// Test 5: Check Database Connection
echo "TEST 5: Database Connection Check\n";
echo "=================================\n";

try {
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Database connection: SUCCESS\n\n";
} catch (Exception $e) {
    echo "❌ ERROR: Database connection failed: " . $e->getMessage() . "\n\n";
}

// Test 6: Check Routes
echo "TEST 6: Payment Routes Check\n";
echo "===========================\n";

try {
    $routes = [
        'payment.checkout' => 'payment/checkout/{registration}',
        'payment.process' => 'payment/process/{registration}',
        'payment.update-method' => 'payment/update-method/{registration}',
        'payment.status' => 'payment/status/{paymentId}',
        'payment.finish' => 'payment/finish/{payment}',
        'payment.error' => 'payment/error/{payment}',
    ];

    foreach ($routes as $name => $uri) {
        try {
            $route = route($name, ['registration' => 1, 'payment' => 1, 'paymentId' => 1]);
            echo "✓ Route '$name': REGISTERED\n";
        } catch (Exception $e) {
            echo "❌ Route '$name': NOT FOUND\n";
        }
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ ERROR: Could not check routes: " . $e->getMessage() . "\n\n";
}

// Test 7: Check Sample Registration
echo "TEST 7: Sample Data Check\n";
echo "=========================\n";

try {
    $registrationCount = \App\Models\Registration::count();
    $paymentCount = \App\Models\Payment::count();
    $userCount = \App\Models\User::count();

    echo "✓ Registrations in database: $registrationCount\n";
    echo "✓ Payments in database: $paymentCount\n";
    echo "✓ Users in database: $userCount\n";

    if ($registrationCount > 0) {
        $sampleReg = \App\Models\Registration::first();
        echo "✓ Sample registration ID: {$sampleReg->id}\n";
        echo "✓ Sample registration status: {$sampleReg->status}\n";
        echo "✅ Sample data available for testing\n\n";
    } else {
        echo "❌ WARNING: No registrations found for testing\n\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: Could not check sample data: " . $e->getMessage() . "\n\n";
}

// Summary
echo "=== DIAGNOSTIC SUMMARY ===\n";

$issues = [];
if (empty($serverKey) || empty($clientKey)) {
    $issues[] = "Midtrans environment variables not configured";
}

if (isset($isDown) && $isDown) {
    $issues[] = "Application is in maintenance mode";
}

if (!isset($configured) || !$configured) {
    $issues[] = "MidtransHelper reports not configured";
}

if (!isset($serviceConfigured) || !$serviceConfigured) {
    $issues[] = "MidtransService reports not configured";
}

if (empty($issues)) {
    echo "✅ All tests passed! Midtrans integration should be working.\n";
    echo "\nNext steps:\n";
    echo "1. Test payment flow in browser\n";
    echo "2. Check browser console for JavaScript errors\n";
    echo "3. Monitor application logs during payment attempts\n";
} else {
    echo "❌ Issues found:\n";
    foreach ($issues as $issue) {
        echo "   - $issue\n";
    }
    echo "\nPlease fix these issues before testing payments.\n";
}

echo "\n=== END DIAGNOSTIC ===\n";
