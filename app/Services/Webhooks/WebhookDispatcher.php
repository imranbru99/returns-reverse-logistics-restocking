<?php

declare(strict_types=1);

namespace App\Services\Webhooks;

use App\Enums\WebhookDeliveryStatus;
use App\Models\Tenant;
use App\Models\WebhookDelivery;
use App\Models\WebhookEndpoint;
use App\Jobs\DeliverWebhookJob;

final class WebhookDispatcher
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function dispatch(Tenant $tenant, string $event, array $payload): void
    {
        $endpoints = WebhookEndpoint::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $tenant->getKey())
            ->where('is_active', true)
            ->get();

        foreach ($endpoints as $endpoint) {
            if (! $endpoint->listensFor($event)) {
                continue;
            }

            $delivery = WebhookDelivery::query()->create([
                'webhook_endpoint_id' => $endpoint->getKey(),
                'event' => $event,
                'payload' => $payload,
                'status' => WebhookDeliveryStatus::Pending,
            ]);

            DeliverWebhookJob::dispatch($delivery->getKey());
        }
    }
}
