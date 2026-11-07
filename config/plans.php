<?php

declare(strict_types=1);

return [
    'currency' => 'USD',
    'billing_period' => 'month',

    'tier_prices' => [
        'free' => 0,
        'starter' => 29,
        'growth' => 79,
        'enterprise' => 199,
    ],

    'plans' => [
        'starter' => [
            'key' => 'starter',
            'name' => 'Starter',
            'price' => 199,
            'description' => 'Core returns intake, RMA, and inspection for growing brands.',
            'included_modules' => [
                'tenant-organization',
                'users-roles-permissions',
                'product-catalog',
                'orders-customers',
                'return-policy-engine',
                'rma-management',
                'inspection-engine',
                'tasks-work-queues',
                'analytics-reporting',
                'documents-pdf-studio',
            ],
            'limits' => [
                'users' => 5,
                'rmas_per_month' => 500,
                'warehouses' => 1,
            ],
        ],
        'growth' => [
            'key' => 'growth',
            'name' => 'Growth',
            'price' => 499,
            'description' => 'Warehouse operations, portal, and disposition for multi-site teams.',
            'included_modules' => [
                'warehouse-management',
                'customer-returns-portal',
                'shipping-labels',
                'receiving-dock',
                'disposition-engine',
                'restocking-putaway',
                'refunds-credits',
                'integrations-hub',
                'customer-support-desk',
            ],
            'limits' => [
                'users' => 25,
                'rmas_per_month' => 5000,
                'warehouses' => 5,
            ],
        ],
        'enterprise' => [
            'key' => 'enterprise',
            'name' => 'Enterprise',
            'price' => null,
            'description' => 'AI assist, API, billing, and executive analytics with dedicated support.',
            'included_modules' => [
                'ai-inspection-assist',
                'api-webhooks',
                'billing-subscriptions',
                'executive-dashboards',
                'fraud-abuse-prevention',
                'resale-marketplace',
                'liquidation-auctions',
            ],
            'limits' => [
                'users' => 'unlimited',
                'rmas_per_month' => 'unlimited',
                'warehouses' => 'unlimited',
            ],
        ],
    ],
];
