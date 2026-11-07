<?php

declare(strict_types=1);

namespace App\Services\Shipping\Adapters;

use App\Enums\ShipmentStatus;
use App\Models\CarrierAccount;
use App\Models\Rma;
use App\Services\Shipping\Contracts\CarrierAdapterInterface;
use Illuminate\Support\Str;

final class OwnCourierAdapter implements CarrierAdapterInterface
{
    public function rate(CarrierAccount $account, Rma $rma, array $parcel = []): array
    {
        return [
            'rate_cents' => 0,
            'currency' => 'USD',
            'service' => 'own_courier',
        ];
    }

    public function createLabel(CarrierAccount $account, Rma $rma, array $parcel = []): array
    {
        $courier = $account->credentials['courier_name'] ?? 'In-house';

        return [
            'tracking_number' => 'OWN-'.Str::upper(Str::random(8)),
            'meta' => [
                'courier' => $courier,
                'pickup_scheduled' => now()->addDay()->toIso8601String(),
            ],
        ];
    }

    public function track(CarrierAccount $account, string $trackingNumber): array
    {
        return [
            'status' => ShipmentStatus::OutForDelivery->value,
            'events' => [
                ['at' => now()->toIso8601String(), 'description' => 'With local courier'],
            ],
        ];
    }
}
