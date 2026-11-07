<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Location;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => fake()->words(2, true),
            'code' => strtoupper(fake()->unique()->bothify('WH-###')),
            'type' => 'warehouse',
            'city' => fake()->city(),
            'country_code' => 'US',
            'is_active' => true,
        ];
    }
}
