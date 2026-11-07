<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RefurbishmentWorkOrderStatus;
use App\Models\RefurbishmentWorkOrder;
use App\Models\RmaItem;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RefurbishmentWorkOrder>
 */
class RefurbishmentWorkOrderFactory extends Factory
{
    protected $model = RefurbishmentWorkOrder::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'rma_item_id' => RmaItem::factory(),
            'status' => RefurbishmentWorkOrderStatus::Pending,
        ];
    }
}
