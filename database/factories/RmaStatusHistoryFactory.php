<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RmaStatusHistory;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RmaStatusHistory>
 */
class RmaStatusHistoryFactory extends Factory
{
    protected $model = RmaStatusHistory::class;

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
