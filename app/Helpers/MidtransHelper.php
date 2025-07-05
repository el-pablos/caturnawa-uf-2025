<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class MidtransHelper
{
    public static function initMidtransConfig()
    {
        // Get config values
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');
        
        // Log configuration for debugging
        Log::info('Midtrans Configuration', [
            'server_key_exists' => !empty($serverKey),
            'client_key_exists' => !empty($clientKey),
            'is_production' => $isProduction
        ]);
        
        if (empty($serverKey) || empty($clientKey)) {
            Log::warning('Midtrans keys not properly configured');
        }
        
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$clientKey = $clientKey;
        \Midtrans\Config::$isProduction = $isProduction;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        
        // Don't set curlOptions as they might be causing the issue
    }
}
