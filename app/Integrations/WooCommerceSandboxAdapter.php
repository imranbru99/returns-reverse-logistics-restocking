<?php

declare(strict_types=1);

namespace App\Integrations;

use App\Contracts\CommerceChannelInterface;
use App\Enums\IntegrationDriver;
use App\Models\IntegrationConnection;

final class WooCommerceSandboxAdapter implements CommerceChannelInterface
{
    public function driver(): string
    {
        return IntegrationDriver::WooCommerce->value;
    }

    public function fetchOrders(IntegrationConnection $connection, ?\DateTimeInterface $since = null): array
    {
        return [
            [
                'external_id' => 'woo-demo-2001',
                'number' => 'WOO-2001',
                'customer_email' => 'sandbox@woo.test',
                'total' => 89.50,
                'placed_at' => now()->subHours(12)->toIso8601String(),
            ],
        ];
    }

    public function testConnection(IntegrationConnection $connection, array $payload = []): bool
    {
        $url = data_get($connection->credentials, 'store_url');

        return is_string($url) && filter_var($url, FILTER_VALIDATE_URL);
    }
}
