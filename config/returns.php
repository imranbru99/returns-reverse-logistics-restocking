<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Demo mode
    |--------------------------------------------------------------------------
    |
    | When enabled, demo safeguards apply: sandboxed payments/mail/carriers,
    | nightly demo:reset scheduling, and the public demo login shortcuts.
    |
    */
    'demo_mode' => (bool) env('DEMO_MODE', false),

    'demo_password' => env('DEMO_PASSWORD', 'DemoPass123!'),

    /*
    |--------------------------------------------------------------------------
    | Demo seed volumes (use demo:reset --heavy for full demo datasets)
    |--------------------------------------------------------------------------
    */
    'demo_seed' => [
        'products' => (int) env('DEMO_SEED_PRODUCTS', 100),
        'orders' => (int) env('DEMO_SEED_ORDERS', 80),
        'rmas' => (int) env('DEMO_SEED_RMAS', 40),
    ],

    /*
    |--------------------------------------------------------------------------
    | Application currency
    |--------------------------------------------------------------------------
    */
    'currency' => [
        'default' => env('APP_CURRENCY', 'USD'),
        'display' => env('APP_CURRENCY_DISPLAY', 'symbol'),
        'supported' => [
            'USD' => ['name' => 'US Dollar', 'symbol' => '$', 'decimals' => 2],
            'BDT' => ['name' => 'Bangladeshi Taka', 'symbol' => '৳', 'decimals' => 2],
            'EUR' => ['name' => 'Euro', 'symbol' => '€', 'decimals' => 2],
            'GBP' => ['name' => 'British Pound', 'symbol' => '£', 'decimals' => 2],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Locales shipped with the platform
    |--------------------------------------------------------------------------
    */
    'locales' => [
        'available' => array_filter(explode(',', (string) env('APP_AVAILABLE_LOCALES', 'en,bn'))),
        'default' => env('APP_LOCALE', 'en'),
        'fallback' => env('APP_FALLBACK_LOCALE', 'en'),
        'rtl' => ['ar', 'he', 'ur'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Document & entity numbering defaults (tenant-overridable via settings)
    |--------------------------------------------------------------------------
    */
    'numbering' => [
        'rma' => [
            'prefix' => env('RETURNS_RMA_PREFIX', 'RMA'),
            'padding' => (int) env('RETURNS_RMA_PADDING', 6),
            'reset' => env('RETURNS_RMA_RESET', 'yearly'),
        ],
        'inspection' => [
            'prefix' => env('RETURNS_INSPECTION_PREFIX', 'INS'),
            'padding' => (int) env('RETURNS_INSPECTION_PADDING', 6),
        ],
        'shipment' => [
            'prefix' => env('RETURNS_SHIPMENT_PREFIX', 'SHP'),
            'padding' => (int) env('RETURNS_SHIPMENT_PADDING', 6),
        ],
        'invoice' => [
            'prefix' => env('RETURNS_INVOICE_PREFIX', 'INV'),
            'padding' => (int) env('RETURNS_INVOICE_PADDING', 6),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Integration sandbox flags (production uses live credentials per tenant)
    |--------------------------------------------------------------------------
    */
    'sandbox' => [
        'payments' => (bool) env('PAYMENTS_SANDBOX', true),
        'carriers' => (bool) env('CARRIERS_SANDBOX', true),
        'sms' => (bool) env('SMS_SANDBOX', true),
        'stripe' => (bool) env('STRIPE_SANDBOX', true),
        'bkash' => (bool) env('BKASH_SANDBOX', true),
        'nagad' => (bool) env('NAGAD_SANDBOX', true),
        'sslcommerz' => (bool) env('SSLCOMMERZ_SANDBOX', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Platform branding defaults
    |--------------------------------------------------------------------------
    */
    'brand' => [
        'name' => env('APP_NAME', 'Returns Platform'),
        'author' => 'Imran Ahmed',
        'author_email' => 'me@imrandev.bd',
        'author_url' => 'https://imrandev.bd',
    ],

    'ai_insights' => [
        'fake_forecast' => (bool) env('AI_INSIGHTS_FAKE_FORECAST', true),
    ],

];
