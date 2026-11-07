<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PutawayTaskStatus;
use App\Models\InventoryItem;
use App\Models\PutawayTask;
use App\Models\Tenant;
use App\Models\WarehouseBin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PutawayTask>
 */
class PutawayTaskFactory extends Factory
{
    protected $model = PutawayTask::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'inventory_item_id' => InventoryItem::factory(),
            'to_bin_id' => WarehouseBin::factory(),
            'status' => PutawayTaskStatus::Pending,
        ];
    }
}
