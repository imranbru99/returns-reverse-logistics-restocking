<?php

declare(strict_types=1);

namespace App\Services\Integrations;

use App\Contracts\CommerceChannelInterface;
use App\Enums\IntegrationConnectionStatus;
use App\Enums\IntegrationDriver;
use App\Integrations\CsvImportAdapter;
use App\Integrations\ShopifySandboxAdapter;
use App\Integrations\WooCommerceSandboxAdapter;
use App\Models\IntegrationConnection;
use App\Models\IntegrationSyncLog;
use InvalidArgumentException;

final class IntegrationManager
{
    /** @var array<string, CommerceChannelInterface> */
    private array $adapters;

    public function __construct(
        ?ShopifySandboxAdapter $shopify = null,
        ?WooCommerceSandboxAdapter $woo = null,
        ?CsvImportAdapter $csv = null,
    ) {
        $shopify ??= new ShopifySandboxAdapter;
        $woo ??= new WooCommerceSandboxAdapter;
        $csv ??= new CsvImportAdapter;

        $this->adapters = [
            $shopify->driver() => $shopify,
            $woo->driver() => $woo,
            $csv->driver() => $csv,
        ];
    }

    public function adapterFor(IntegrationConnection $connection): CommerceChannelInterface
    {
        $driver = $connection->driver instanceof IntegrationDriver
            ? $connection->driver->value
            : (string) $connection->driver;

        if (! isset($this->adapters[$driver])) {
            throw new InvalidArgumentException("No adapter registered for driver [{$driver}].");
        }

        return $this->adapters[$driver];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function syncOrders(IntegrationConnection $connection): array
    {
        $adapter = $this->adapterFor($connection);

        $log = IntegrationSyncLog::query()->create([
            'tenant_id' => $connection->tenant_id,
            'integration_connection_id' => $connection->getKey(),
            'status' => 'running',
            'started_at' => now(),
        ]);

        $connection->forceFill(['status' => IntegrationConnectionStatus::Syncing])->save();

        try {
            $orders = $adapter->fetchOrders($connection);
            $connection->forceFill([
                'status' => IntegrationConnectionStatus::Connected,
                'last_synced_at' => now(),
            ])->save();

            $log->forceFill([
                'status' => 'completed',
                'message' => 'Synced '.count($orders).' orders',
                'payload' => ['orders' => $orders],
                'finished_at' => now(),
            ])->save();

            return $orders;
        } catch (\Throwable $exception) {
            $connection->forceFill(['status' => IntegrationConnectionStatus::Error])->save();
            $log->forceFill([
                'status' => 'failed',
                'message' => $exception->getMessage(),
                'finished_at' => now(),
            ])->save();

            throw $exception;
        }
    }
}
