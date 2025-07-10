<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Registration;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== UNAS Fest 2025 Checkout Page Test ===\n\n";

try {
    // Login as test user
    $testUser = User::where('email', 'peserta1@unasfest.com')->first();
    if (!$testUser) {
        echo "Test user not found\n";
        exit;
    }
    
    Auth::login($testUser);
    echo "Logged in as: {$testUser->email}\n";
    
    // Get test registration
    $registration = Registration::where('user_id', $testUser->id)->first();
    if (!$registration) {
        echo "No registration found for user\n";
        exit;
    }
    
    echo "Testing registration ID: {$registration->id}\n";
    echo "Competition: {$registration->competition->name}\n";
    echo "Amount: Rp " . number_format($registration->amount) . "\n\n";
    
    // Test PaymentController checkout method
    echo "Testing PaymentController::checkout method:\n";
    $paymentController = new PaymentController();
    
    // Create a mock request
    $request = Request::create('/payment/checkout/' . $registration->id, 'GET');
    $request->setUserResolver(function () use ($testUser) {
        return $testUser;
    });
    
    try {
        $response = $paymentController->checkout($request, $registration);
        echo "✅ Checkout method executed successfully\n";
        echo "Response type: " . get_class($response) . "\n";
        
        if (method_exists($response, 'getStatusCode')) {
            echo "Status code: " . $response->getStatusCode() . "\n";
        }
        
        // Check if it's a view response
        if ($response instanceof \Illuminate\View\View || 
            (method_exists($response, 'getOriginalContent') && 
             $response->getOriginalContent() instanceof \Illuminate\View\View)) {
            echo "✅ View response generated\n";
            
            // Get view data
            $view = method_exists($response, 'getOriginalContent') ? 
                    $response->getOriginalContent() : $response;
            
            if (method_exists($view, 'getData')) {
                $data = $view->getData();
                echo "View data keys: " . implode(', ', array_keys($data)) . "\n";
                
                if (isset($data['registration'])) {
                    echo "✅ Registration data passed to view\n";
                }
                if (isset($data['globalConfig'])) {
                    echo "✅ Global config passed to view\n";
                    if (isset($data['globalConfig']['midtrans_configured'])) {
                        echo "Midtrans configured: " . ($data['globalConfig']['midtrans_configured'] ? 'YES' : 'NO') . "\n";
                    }
                }
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Checkout method error: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
    
    echo "\n";
    
    // Test PaymentController process method
    echo "Testing PaymentController::process method:\n";
    try {
        $processRequest = Request::create('/payment/process/' . $registration->id, 'POST', [
            'payment_method' => 'credit_card'
        ]);
        $processRequest->setUserResolver(function () use ($testUser) {
            return $testUser;
        });
        
        $processResponse = $paymentController->process($processRequest, $registration);
        echo "✅ Process method executed successfully\n";
        
        if (method_exists($processResponse, 'getContent')) {
            $content = $processResponse->getContent();
            $data = json_decode($content, true);
            
            if ($data) {
                echo "Response data:\n";
                echo "- Success: " . ($data['success'] ?? 'not set') . "\n";
                if (isset($data['snap_token'])) {
                    echo "- Snap token: " . substr($data['snap_token'], 0, 20) . "...\n";
                }
                if (isset($data['message'])) {
                    echo "- Message: " . $data['message'] . "\n";
                }
            } else {
                echo "Response content: " . substr($content, 0, 200) . "...\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Process method error: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ General error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
