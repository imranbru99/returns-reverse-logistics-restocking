<?php

declare(strict_types=1);

use App\Enums\WebhookDeliveryStatus;
use App\Jobs\DeliverWebhookJob;
use App\Models\Tenant;
use App\Models\WebhookDelivery;
use App\Models\WebhookEndpoint;
use App\Services\Webhooks\WebhookDispatcher;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

it('queues signed webhook deliveries for matching endpoints', function (): void {
    Queue::fake();

    $tenant = Tenant::factory()->create();
    WebhookEndpoint::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'url' => 'https://hooks.test/rma',
        'events' => ['rma.created'],
    ]);

    app(WebhookDispatcher::class)->dispatch($tenant, 'rma.created', ['number' => 'RMA-1']);

    expect(WebhookDelivery::query()->count())->toBe(1);
    Queue::assertPushed(DeliverWebhookJob::class);
});

it('delivers webhooks with an HMAC signature', function (): void {
    Http::fake(['https://hooks.test/*' => Http::response('ok', 200)]);

    $tenant = Tenant::factory()->create();
    $endpoint = WebhookEndpoint::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'url' => 'https://hooks.test/receive',
        'secret' => 'test-secret',
    ]);

    $delivery = WebhookDelivery::query()->create([
        'webhook_endpoint_id' => $endpoint->getKey(),
        'event' => 'rma.created',
        'payload' => ['number' => 'RMA-42'],
        'status' => WebhookDeliveryStatus::Pending,
    ]);

    (new DeliverWebhookJob($delivery->getKey()))->handle();

    Http::assertSent(function ($request) {
        return $request->hasHeader('X-Returns-Signature')
            && $request->hasHeader('X-Returns-Event', 'rma.created');
    });

    expect($delivery->fresh()->status)->toBe(WebhookDeliveryStatus::Delivered);
});
