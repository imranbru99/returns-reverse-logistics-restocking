<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\WarehouseZone;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WarehouseZone>
 */
class WarehouseZoneFactory extends Factory
{
    protected $model = WarehouseZone::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
        ];
    }
}
