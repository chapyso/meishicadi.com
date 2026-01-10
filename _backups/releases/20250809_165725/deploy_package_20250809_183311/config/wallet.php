<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wallet Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for Apple Wallet and Google Wallet
    | integration.
    |
    */

    'apple' => [
        'enabled' => env('APPLE_WALLET_ENABLED', false),
        'certificate_path' => env('APPLE_WALLET_CERTIFICATE_PATH', storage_path('certificates/apple_wallet.p12')),
        'certificate_password' => env('APPLE_WALLET_CERTIFICATE_PASSWORD', ''),
        'team_identifier' => env('APPLE_WALLET_TEAM_IDENTIFIER', ''),
        'pass_type_identifier' => env('APPLE_WALLET_PASS_TYPE_IDENTIFIER', ''),
    ],

    'google' => [
        'enabled' => env('GOOGLE_WALLET_ENABLED', false),
        'service_account_email' => env('GOOGLE_WALLET_SERVICE_ACCOUNT_EMAIL', ''),
        'private_key' => env('GOOGLE_WALLET_PRIVATE_KEY', ''),
        'issuer_id' => env('GOOGLE_WALLET_ISSUER_ID', ''),
        'class_id' => env('GOOGLE_WALLET_CLASS_ID', 'business_card'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pass Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for wallet passes
    |
    */

    'pass' => [
        'expires_after_days' => env('WALLET_PASS_EXPIRES_AFTER_DAYS', 365),
        'max_downloads' => env('WALLET_PASS_MAX_DOWNLOADS', 1000),
    ],
]; 