<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'tenant_id' => Tenant::factory(),
            'name' => $name,
            'slug' => str()->slug($name).'-'.fake()->unique()->numerify('###'),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####')),
            'price' => fake()->randomFloat(2, 5, 500),
            'currency' => 'USD',
            'is_returnable' => true,
            'is_active' => true,
        ];
    }
}
