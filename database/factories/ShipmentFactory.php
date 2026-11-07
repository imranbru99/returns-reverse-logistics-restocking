<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ShipmentDirection;
use App\Enums\ShipmentStatus;
use App\Models\Rma;
use App\Models\Shipment;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shipment>
 */
class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'rma_id' => Rma::factory(),
            'direction' => ShipmentDirection::Inbound,
            'carrier' => 'manual',
            'tracking_number' => 'TRK-'.fake()->unique()->numerify('########'),
            'status' => ShipmentStatus::Draft,
            'rate_cents' => 899,
        ];
    }
}
