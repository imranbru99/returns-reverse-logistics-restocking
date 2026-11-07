<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'legal_name' => $name.' LLC',
            'email' => fake()->unique()->companyEmail(),
            'timezone' => 'UTC',
            'locale' => 'en',
            'currency' => 'USD',
            'is_active' => true,
        ];
    }
}
