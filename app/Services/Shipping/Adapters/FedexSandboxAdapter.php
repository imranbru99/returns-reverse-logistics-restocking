<?php

declare(strict_types=1);

namespace App\Services\Shipping\Adapters;

use App\Enums\ShipmentStatus;
use App\Models\CarrierAccount;
use App\Models\Rma;
use App\Services\Shipping\Contracts\CarrierAdapterInterface;
use Illuminate\Support\Str;

final class FedexSandboxAdapter implements CarrierAdapterInterface
{
    public function rate(CarrierAccount $account, Rma $rma, array $parcel = []): array
    {
        return [
            'rate_cents' => 1499,
            'currency' => 'USD',
            'service' => 'fedex_home_sandbox',
        ];
    }

    public function createLabel(CarrierAccount $account, Rma $rma, array $parcel = []): array
    {
        return [
            'tracking_number' => 'FDX'.Str::upper(Str::random(12)),
            'meta' => ['sandbox' => true, 'carrier' => 'fedex', 'reference' => Str::upper(Str::random(6))],
        ];
    }

    public function track(CarrierAccount $account, string $trackingNumber): array
    {
        return [
            'status' => ShipmentStatus::LabelCreated->value,
            'events' => [
                ['at' => now()->toIso8601String(), 'description' => 'FedEx sandbox label created'],
            ],
        ];
    }
}
