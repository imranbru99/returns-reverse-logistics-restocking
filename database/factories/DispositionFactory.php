<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DispositionType;
use App\Models\Disposition;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Disposition>
 */
class DispositionFactory extends Factory
{
    protected $model = Disposition::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'type' => DispositionType::RestockAsNew,
            'quantity' => 1,
        ];
    }
}
