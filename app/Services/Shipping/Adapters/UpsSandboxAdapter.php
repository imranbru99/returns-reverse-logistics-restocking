<?php

declare(strict_types=1);

namespace App\Services\Shipping\Adapters;

use App\Enums\ShipmentStatus;
use App\Models\CarrierAccount;
use App\Models\Rma;
use App\Services\Shipping\Contracts\CarrierAdapterInterface;
use Illuminate\Support\Str;

final class UpsSandboxAdapter implements CarrierAdapterInterface
{
    public function rate(CarrierAccount $account, Rma $rma, array $parcel = []): array
    {
        return [
            'rate_cents' => 1299,
            'currency' => 'USD',
            'service' => 'ups_ground_sandbox',
        ];
    }

    public function createLabel(CarrierAccount $account, Rma $rma, array $parcel = []): array
    {
        return [
            'tracking_number' => '1Z'.Str::upper(Str::random(16)),
            'meta' => ['sandbox' => true, 'carrier' => 'ups'],
        ];
    }

    public function track(CarrierAccount $account, string $trackingNumber): array
    {
        return [
            'status' => ShipmentStatus::InTransit->value,
            'events' => [
                ['at' => now()->toIso8601String(), 'description' => 'UPS sandbox scan'],
            ],
        ];
    }
}
