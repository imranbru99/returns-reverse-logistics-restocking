<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RmaItem;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RmaItem>
 */
class RmaItemFactory extends Factory
{
    protected $model = RmaItem::class;

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
