<?php

declare(strict_types=1);

namespace App\Services\Shipping\Contracts;

use App\Models\CarrierAccount;
use App\Models\Rma;

interface CarrierAdapterInterface
{
    /**
     * @param  array<string, mixed>  $parcel
     * @return array{rate_cents: int, currency: string, service: string}
     */
    public function rate(CarrierAccount $account, Rma $rma, array $parcel = []): array;

    /**
     * @param  array<string, mixed>  $parcel
     * @return array{tracking_number: string, label_url?: string, meta?: array<string, mixed>}
     */
    public function createLabel(CarrierAccount $account, Rma $rma, array $parcel = []): array;

    /**
     * @return array{status: string, events: array<int, array<string, mixed>>}
     */
    public function track(CarrierAccount $account, string $trackingNumber): array;
}
