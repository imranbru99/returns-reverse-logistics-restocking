<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ReturnPolicy;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReturnPolicy>
 */
class ReturnPolicyFactory extends Factory
{
    protected $model = ReturnPolicy::class;

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
