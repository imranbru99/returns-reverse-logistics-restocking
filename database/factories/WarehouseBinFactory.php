<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\WarehouseBin;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WarehouseBin>
 */
class WarehouseBinFactory extends Factory
{
    protected $model = WarehouseBin::class;

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
