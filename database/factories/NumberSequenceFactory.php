<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\NumberSequence;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NumberSequence>
 */
class NumberSequenceFactory extends Factory
{
    protected $model = NumberSequence::class;

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
