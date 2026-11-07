<?php

declare(strict_types=1);

return [
    'app_name' => 'Returns & Reverse Logistics',
    'tagline' => 'Inspect, restock, and recover value from every return.',
    'modules' => [
        'enabled' => 'Module enabled',
        'disabled' => 'Module disabled',
        'dependency_required' => 'This module requires :modules to be enabled first.',
        'has_dependents' => 'Disable dependent modules first: :modules',
    ],
    'tenancy' => [
        'select_organization' => 'Select organization',
        'no_access' => 'You do not have access to this organization.',
    ],
    'auth' => [
        'demo_banner' => 'Demo mode is active. Outbound emails, SMS, payments, and carriers are sandboxed.',
    ],
];
