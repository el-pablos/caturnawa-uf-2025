<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class MidtransHelper
{
    public static function initMidtransConfig()
    {
        // Get config values directly from environment with fallbacks
        $serverKey = env('MIDTRANS_SERVER_KEY', '');
        $clientKey = env('MIDTRANS_CLIENT_KEY', '');
        $isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        $isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        $is3ds = env('MIDTRANS_IS_3DS', true);

        // Log configuration for debugging
        Log::info('Midtrans Configuration', [
            'server_key_exists' => !empty($serverKey),
            'client_key_exists' => !empty($clientKey),
            'is_production' => $isProduction,
            'is_sanitized' => $isSanitized,
            'is_3ds' => $is3ds
        ]);

        // Only configure Midtrans if keys are available
        if (!empty($serverKey) && !empty($clientKey)) {
            // Set Midtrans configuration
            \Midtrans\Config::$serverKey = $serverKey;
            \Midtrans\Config::$isProduction = $isProduction;
            \Midtrans\Config::$isSanitized = $isSanitized;
            \Midtrans\Config::$is3ds = $is3ds;

            Log::info('Midtrans configuration initialized successfully');
        } else {
            Log::warning('Midtrans keys not configured - payment features will be disabled');
        }
    }

    /**
     * Check if Midtrans is properly configured
     *
     * @return bool
     */
    public static function isConfigured(): bool
    {
        $serverKey = env('MIDTRANS_SERVER_KEY', '');
        $clientKey = env('MIDTRANS_CLIENT_KEY', '');

        return !empty($serverKey) && !empty($clientKey);
    }

    /**
     * Get client key for frontend
     *
     * @return string
     */
    public static function getClientKey(): string
    {
        return env('MIDTRANS_CLIENT_KEY', '');
    }

    /**
     * Get server key for backend
     *
     * @return string
     */
    public static function getServerKey(): string
    {
        return env('MIDTRANS_SERVER_KEY', '');
    }

    /**
     * Check if running in production mode
     *
     * @return bool
     */
    public static function isProduction(): bool
    {
        return env('MIDTRANS_IS_PRODUCTION', false);
    }

    /**
     * Get appropriate Snap JS URL based on environment
     *
     * @return string
     */
    public static function getSnapJsUrl(): string
    {
        if (self::isProduction()) {
            return 'https://app.midtrans.com/snap/snap.js';
        } else {
            return 'https://app.sandbox.midtrans.com/snap/snap.js';
        }
    }

    /**
     * Get appropriate API base URL based on environment
     *
     * @return string
     */
    public static function getApiBaseUrl(): string
    {
        if (self::isProduction()) {
            return 'https://api.midtrans.com/v2';
        } else {
            return 'https://api.sandbox.midtrans.com/v2';
        }
    }

    /**
     * Get environment-specific configuration array
     *
     * @return array
     */
    public static function getEnvironmentConfig(): array
    {
        return [
            'is_production' => self::isProduction(),
            'server_key' => self::getServerKey(),
            'client_key' => self::getClientKey(),
            'snap_js_url' => self::getSnapJsUrl(),
            'api_base_url' => self::getApiBaseUrl(),
            'environment' => self::isProduction() ? 'production' : 'sandbox',
        ];
    }
}
