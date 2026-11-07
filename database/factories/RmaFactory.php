<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RmaStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Rma;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rma>
 */
class RmaFactory extends Factory
{
    protected $model = Rma::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'customer_id' => Customer::factory(),
            'order_id' => Order::factory(),
            'number' => 'RMA-'.fake()->unique()->numerify('######'),
            'status' => RmaStatus::Requested,
            'reason' => fake()->sentence(),
        ];
    }
}
