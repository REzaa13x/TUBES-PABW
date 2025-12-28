<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Midtrans payment gateway
    |
    */

    // Server key dari Midtrans
    'server_key' => env('MIDTRANS_SERVER_KEY'),

    // Client key dari Midtrans
    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    // Production mode (true) or sandbox mode (false)
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Enable sanitization
    'is_sanitized' => env('MIDTRANS_SANITIZED', false),

    // Enable 3D-Secure
    'is_3ds' => env('MIDTRANS_3DS', true),

    // Currency
    'currency' => 'IDR',

    // Payment options that are allowed
    'enabled_payments' => ['credit_card', 'bank_transfer', 'gopay', 'shopeepay', 'ovo', 'dana', 'qris'],

    // Custom field names in transaction details
    'custom_field_names' => [
        'donation_id' => 'donation_id',
        'campaign_id' => 'campaign_id',
        'donor_name' => 'donor_name',
        'donor_email' => 'donor_email',
    ],
];