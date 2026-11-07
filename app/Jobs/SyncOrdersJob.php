<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\IntegrationConnection;
use App\Services\Integrations\IntegrationManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class SyncOrdersJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $integrationConnectionId,
    ) {}

    public function handle(IntegrationManager $manager): void
    {
        $connection = IntegrationConnection::query()->withoutGlobalScopes()->find($this->integrationConnectionId);

        if ($connection === null) {
            return;
        }

        $manager->syncOrders($connection);
    }
}
