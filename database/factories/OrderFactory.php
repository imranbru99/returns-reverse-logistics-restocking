<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 1000);

        return [
            'tenant_id' => Tenant::factory(),
            'customer_id' => Customer::factory(),
            'number' => 'ORD-'.fake()->unique()->numerify('######'),
            'status' => OrderStatus::Delivered,
            'subtotal' => $subtotal,
            'tax' => round($subtotal * 0.08, 2),
            'shipping' => 9.99,
            'total' => $subtotal + round($subtotal * 0.08, 2) + 9.99,
            'currency' => 'USD',
            'placed_at' => now()->subDays(10),
            'delivered_at' => now()->subDays(3),
        ];
    }
}
