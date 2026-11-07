<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ReceivingRecord;
use App\Models\Rma;
use App\Models\Tenant;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReceivingRecord>
 */
class ReceivingRecordFactory extends Factory
{
    protected $model = ReceivingRecord::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'rma_id' => Rma::factory(),
            'warehouse_id' => Warehouse::factory(),
            'expected_qty' => 1,
            'received_qty' => 1,
            'received_at' => now(),
        ];
    }
}
