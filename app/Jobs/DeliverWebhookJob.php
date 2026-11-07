<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\WebhookDeliveryStatus;
use App\Models\WebhookDelivery;
use App\Models\WebhookEndpoint;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

final class DeliverWebhookJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public function __construct(
        public readonly string $webhookDeliveryId,
    ) {}

    public function handle(): void
    {
        $delivery = WebhookDelivery::query()->find($this->webhookDeliveryId);

        if ($delivery === null) {
            return;
        }

        $endpoint = WebhookEndpoint::query()->withoutGlobalScopes()->find($delivery->webhook_endpoint_id);

        if ($endpoint === null) {
            return;
        }

        $body = json_encode([
            'id' => $delivery->getKey(),
            'event' => $delivery->event,
            'payload' => $delivery->payload,
            'created_at' => $delivery->created_at?->toIso8601String(),
        ], JSON_THROW_ON_ERROR);

        $signature = hash_hmac('sha256', $body, (string) $endpoint->secret);

        $response = Http::timeout(15)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'X-Returns-Signature' => $signature,
                'X-Returns-Event' => $delivery->event,
                'X-Returns-Delivery' => $delivery->getKey(),
            ])
            ->withBody($body, 'application/json')
            ->post($endpoint->url);

        $delivery->increment('attempts');

        if ($response->successful()) {
            $delivery->forceFill([
                'status' => WebhookDeliveryStatus::Delivered,
                'response_code' => $response->status(),
                'response_body' => Str::limit($response->body(), 2000),
                'delivered_at' => now(),
                'next_retry_at' => null,
            ])->save();

            return;
        }

        $retryAt = now()->addMinutes(min(60, 2 ** $delivery->attempts));

        $delivery->forceFill([
            'status' => $delivery->attempts >= $this->tries
                ? WebhookDeliveryStatus::Failed
                : WebhookDeliveryStatus::Pending,
            'response_code' => $response->status(),
            'response_body' => Str::limit($response->body(), 2000),
            'next_retry_at' => $delivery->attempts >= $this->tries ? null : $retryAt,
        ])->save();

        if ($delivery->attempts < $this->tries) {
            self::dispatch($delivery->getKey())->delay($retryAt);
        }
    }
}
