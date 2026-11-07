<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\WarehouseAisle;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WarehouseAisle>
 */
class WarehouseAisleFactory extends Factory
{
    protected $model = WarehouseAisle::class;

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
