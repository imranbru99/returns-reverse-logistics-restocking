<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\InspectionStatus;
use App\Models\Inspection;
use App\Models\Rma;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inspection>
 */
class InspectionFactory extends Factory
{
    protected $model = Inspection::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'rma_id' => Rma::factory(),
            'number' => 'INS-'.fake()->unique()->numerify('######'),
            'status' => InspectionStatus::Pending,
        ];
    }
}
