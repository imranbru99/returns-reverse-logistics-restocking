<?php

declare(strict_types=1);

namespace App\Integrations;

use App\Contracts\CommerceChannelInterface;
use App\Enums\IntegrationDriver;
use App\Models\IntegrationConnection;

final class ShopifySandboxAdapter implements CommerceChannelInterface
{
    public function driver(): string
    {
        return IntegrationDriver::Shopify->value;
    }

    public function fetchOrders(IntegrationConnection $connection, ?\DateTimeInterface $since = null): array
    {
        return [
            [
                'external_id' => 'shopify-demo-1001',
                'number' => 'SHOP-1001',
                'customer_email' => 'sandbox@shopify.test',
                'total' => 129.99,
                'placed_at' => now()->subDay()->toIso8601String(),
            ],
        ];
    }

    public function testConnection(IntegrationConnection $connection, array $payload = []): bool
    {
        $shop = data_get($connection->credentials, 'shop_domain');

        return is_string($shop) && $shop !== '';
    }
}
