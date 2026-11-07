<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CarrierDriver;
use App\Models\CarrierAccount;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CarrierAccount>
 */
class CarrierAccountFactory extends Factory
{
    protected $model = CarrierAccount::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'driver' => CarrierDriver::Manual,
            'name' => 'Default manual carrier',
            'credentials' => [],
            'is_default' => true,
            'sandbox' => true,
        ];
    }
}
