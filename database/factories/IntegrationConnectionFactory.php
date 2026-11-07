<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\IntegrationConnectionStatus;
use App\Enums\IntegrationDriver;
use App\Models\IntegrationConnection;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IntegrationConnection>
 */
class IntegrationConnectionFactory extends Factory
{
    protected $model = IntegrationConnection::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'driver' => IntegrationDriver::Csv,
            'name' => 'CSV import',
            'settings' => ['csv_path' => 'imports/orders.csv'],
            'status' => IntegrationConnectionStatus::Disconnected,
        ];
    }
}
