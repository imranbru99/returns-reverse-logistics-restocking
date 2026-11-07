<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\InspectionChecklist;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InspectionChecklist>
 */
class InspectionChecklistFactory extends Factory
{
    protected $model = InspectionChecklist::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => fake()->words(3, true).' checklist',
            'steps' => [
                ['key' => 'visual', 'label' => 'Visual inspection', 'weight' => 40, 'pass' => true],
                ['key' => 'functional', 'label' => 'Functional test', 'weight' => 60, 'pass' => true],
            ],
            'required_evidence' => ['photo_front', 'photo_damage'],
            'is_active' => true,
        ];
    }
}
