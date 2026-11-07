<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        $key = fake()->unique()->slug(2);

        return [
            'key' => $key,
            'name' => ucfirst($key),
            'price' => 199,
            'currency' => 'USD',
            'limits' => ['rmas_per_month' => 500],
            'included_modules' => ['rma-management'],
            'is_active' => true,
        ];
    }
}
