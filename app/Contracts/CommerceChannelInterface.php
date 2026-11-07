<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\IntegrationConnection;

interface CommerceChannelInterface
{
    public function driver(): string;

    /**
     * @return list<array<string, mixed>>
     */
    public function fetchOrders(IntegrationConnection $connection, ?\DateTimeInterface $since = null): array;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function testConnection(IntegrationConnection $connection, array $payload = []): bool;
}
