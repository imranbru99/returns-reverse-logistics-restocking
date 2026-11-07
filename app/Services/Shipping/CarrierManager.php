<?php

declare(strict_types=1);

namespace App\Services\Shipping;

use App\Enums\CarrierDriver;
use App\Models\CarrierAccount;
use App\Services\Shipping\Adapters\FedexSandboxAdapter;
use App\Services\Shipping\Adapters\ManualCarrierAdapter;
use App\Services\Shipping\Adapters\OwnCourierAdapter;
use App\Services\Shipping\Adapters\UpsSandboxAdapter;
use App\Services\Shipping\Contracts\CarrierAdapterInterface;
use InvalidArgumentException;

final class CarrierManager
{
    public function resolve(CarrierAccount $account): CarrierAdapterInterface
    {
        return match ($account->driver) {
            CarrierDriver::Manual => app(ManualCarrierAdapter::class),
            CarrierDriver::OwnCourier => app(OwnCourierAdapter::class),
            CarrierDriver::UpsSandbox => app(UpsSandboxAdapter::class),
            CarrierDriver::FedexSandbox => app(FedexSandboxAdapter::class),
            default => throw new InvalidArgumentException("Unsupported carrier driver [{$account->driver->value}]."),
        };
    }

    public function defaultForTenant(string $tenantId): ?CarrierAccount
    {
        return CarrierAccount::query()
            ->where('tenant_id', $tenantId)
            ->where('is_default', true)
            ->first()
            ?? CarrierAccount::query()->where('tenant_id', $tenantId)->first();
    }
}
