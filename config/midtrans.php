<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi Midtrans Payment Gateway
    | Digunakan untuk memproses pembayaran pendaftaran kompetisi
    |
    */

    /**
     * Midtrans Server Key
     * Dapatkan dari Dashboard Midtrans > Settings > Access Keys
     */
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    /**
     * Midtrans Client Key  
     * Digunakan untuk frontend/JavaScript
     */
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    /**
     * Environment Mode
     * true = Production, false = Sandbox
     */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /**
     * Sanitized Mode
     * true = Aktifkan sanitasi otomatis untuk mencegah XSS
     */
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),

    /**
     * 3DS Mode
     * true = Aktifkan 3D Secure untuk kartu kredit
     */
    'is_3ds' => env('MIDTRANS_IS_3DS', true),

    /**
     * Payment Methods yang diaktifkan
     * Kosongkan array untuk mengaktifkan semua metode
     */
    'enabled_payments' => [
        'credit_card',
        'gopay',
        'shopeepay', 
        'other_qris',
        'bank_transfer',
        'echannel',
        'permata',
        'bca',
        'bni',
        'bri',
        'cstore',
    ],

    /**
     * Custom Expiry
     * Format: [unit, duration]
     * unit: minutes, hours, days
     */
    'custom_expiry' => [
        'unit' => 'hours',
        'duration' => 24,
    ],

    /**
     * Callback URLs
     */
    'callbacks' => [
        'finish' => env('APP_URL') . '/payment/finish',
        'unfinish' => env('APP_URL') . '/payment/unfinish', 
        'error' => env('APP_URL') . '/payment/error',
        'notification' => env('APP_URL') . '/payment/notification',
    ],

    /**
     * Credit Card Configuration
     */
    'credit_card' => [
        'secure' => true,
        'channel' => 'migs',
        'bank' => 'bca',
        'installment' => [
            'required' => false,
            'terms' => [
                'bni' => [3, 6, 12],
                'mandiri' => [3, 6, 12],
                'cimb' => [3],
                'bca' => [3, 6, 12],
                'offline' => [6, 12],
            ],
        ],
        'whitelist_bins' => [],
    ],

    /**
     * Bank Transfer Configuration
     */
    'bank_transfer' => [
        'bank' => 'permata',
        'va_number' => '',
        'free_text' => [
            'inquiry' => [
                'id' => 'Pembayaran UNAS Fest 2025',
                'en' => 'UNAS Fest 2025 Payment',
            ],
            'payment' => [
                'id' => 'Terima kasih telah mendaftar UNAS Fest 2025',
                'en' => 'Thank you for registering UNAS Fest 2025',
            ],
        ],
    ],

    /**
     * GoPay Configuration
     */
    'gopay' => [
        'enable_callback' => true,
        'callback_url' => env('APP_URL') . '/payment/gopay-callback',
    ],

    /**
     * ShopeePay Configuration
     */
    'shopeepay' => [
        'callback_url' => env('APP_URL') . '/payment/shopeepay-callback',
    ],

    /**
     * QRIS Configuration
     */
    'qris' => [
        'acquirer' => 'gopay',
    ],

    /**
     * E-Channel (Mandiri Bill Payment) Configuration
     */
    'echannel' => [
        'bill_info1' => 'Payment For:',
        'bill_info2' => 'UNAS Fest 2025',
    ],

    /**
     * Convenience Store Configuration
     */
    'cstore' => [
        'store' => 'indomaret',
        'message' => 'Pembayaran UNAS Fest 2025',
    ],

    /**
     * Custom Fields untuk tracking
     */
    'custom_fields' => [
        'custom_field1' => 'registration_number',
        'custom_field2' => 'competition_slug', 
        'custom_field3' => 'app_name',
    ],

    /**
     * BIN (Bank Identification Number) yang diblokir
     * Format: ['411111', '424242']
     */
    'blocked_bins' => [],

    /**
     * Maksimal jumlah pembayaran per transaksi
     */
    'max_amount' => 999999999,

    /**
     * Minimal jumlah pembayaran per transaksi  
     */
    'min_amount' => 1000,

    /**
     * Currency code (IDR untuk Indonesia)
     */
    'currency' => 'IDR',

    /**
     * Locale settings
     */
    'locale' => 'id',

    /**
     * Log configuration
     */
    'log' => [
        'enabled' => env('MIDTRANS_LOG_ENABLED', true),
        'level' => env('MIDTRANS_LOG_LEVEL', 'info'),
        'channel' => env('MIDTRANS_LOG_CHANNEL', 'single'),
    ],

];
