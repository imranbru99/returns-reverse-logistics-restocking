<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\WebhookEndpoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WebhookEndpoint>
 */
class WebhookEndpointFactory extends Factory
{
    protected $model = WebhookEndpoint::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'url' => 'https://example.test/webhooks/returns',
            'events' => ['rma.created'],
            'is_active' => true,
        ];
    }
}
