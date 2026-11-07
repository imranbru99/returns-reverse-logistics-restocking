<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\VendorClaimStatus;
use App\Models\RmaItem;
use App\Models\Tenant;
use App\Models\VendorClaim;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VendorClaim>
 */
class VendorClaimFactory extends Factory
{
    protected $model = VendorClaim::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'rma_item_id' => RmaItem::factory(),
            'supplier_name' => fake()->company(),
            'status' => VendorClaimStatus::Draft,
            'claim_amount_cents' => fake()->numberBetween(1000, 50000),
        ];
    }
}
