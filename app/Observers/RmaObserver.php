<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Rma;
use App\Models\Tenant;
use App\Services\Billing\SubscriptionService;
use App\Services\Webhooks\WebhookDispatcher;
use App\Support\TenantContext;

final class RmaObserver
{
    public function __construct(
        private readonly SubscriptionService $subscriptions,
        private readonly WebhookDispatcher $webhooks,
    ) {}

    public function created(Rma $rma): void
    {
        $tenant = Tenant::query()->find($rma->tenant_id) ?? TenantContext::get();

        if ($tenant !== null) {
            $this->subscriptions->meterRmaUsage($tenant);
            $this->webhooks->dispatch($tenant, 'rma.created', [
                'id' => $rma->getKey(),
                'number' => $rma->number,
                'status' => $rma->status->value,
            ]);
        }
    }
}
