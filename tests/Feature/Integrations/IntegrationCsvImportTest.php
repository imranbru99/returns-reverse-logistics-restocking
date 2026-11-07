<?php

declare(strict_types=1);

use App\Enums\IntegrationDriver;
use App\Models\IntegrationConnection;
use App\Models\Tenant;
use App\Services\Integrations\IntegrationManager;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Storage;

it('imports orders from a CSV integration connection', function (): void {
    Storage::fake('local');
    Storage::disk('local')->put('imports/orders.csv', implode("\n", [
        'order_number,customer_email,total,placed_at',
        'CSV-9001,buyer@example.com,55.00,2026-01-01T00:00:00Z',
    ]));

    $tenant = Tenant::factory()->create();

    TenantContext::run($tenant, function () use ($tenant): void {
        $connection = IntegrationConnection::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'driver' => IntegrationDriver::Csv,
            'settings' => ['csv_path' => 'imports/orders.csv'],
        ]);

        $orders = app(IntegrationManager::class)->syncOrders($connection);

        expect($orders)->toHaveCount(1)
            ->and($orders[0]['number'])->toBe('CSV-9001')
            ->and($connection->fresh()->last_synced_at)->not->toBeNull();
    });
});
