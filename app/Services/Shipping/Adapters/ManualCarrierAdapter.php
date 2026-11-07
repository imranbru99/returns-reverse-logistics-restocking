<?php

declare(strict_types=1);

namespace App\Services\Shipping\Adapters;

use App\Enums\ShipmentStatus;
use App\Models\CarrierAccount;
use App\Models\Rma;
use App\Services\Shipping\Contracts\CarrierAdapterInterface;
use Illuminate\Support\Str;

final class ManualCarrierAdapter implements CarrierAdapterInterface
{
    public function rate(CarrierAccount $account, Rma $rma, array $parcel = []): array
    {
        return [
            'rate_cents' => (int) ($parcel['rate_cents'] ?? 799),
            'currency' => 'USD',
            'service' => 'manual_ground',
        ];
    }

    public function createLabel(CarrierAccount $account, Rma $rma, array $parcel = []): array
    {
        return [
            'tracking_number' => 'MAN-'.Str::upper(Str::random(10)),
            'meta' => [
                'driver' => $account->driver->value,
                'sandbox' => $account->sandbox,
            ],
        ];
    }

    public function track(CarrierAccount $account, string $trackingNumber): array
    {
        return [
            'status' => ShipmentStatus::InTransit->value,
            'events' => [
                [
                    'at' => now()->subHours(2)->toIso8601String(),
                    'description' => 'Label created',
                ],
                [
                    'at' => now()->toIso8601String(),
                    'description' => 'In transit',
                ],
            ],
        ];
    }
}
