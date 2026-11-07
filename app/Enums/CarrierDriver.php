<?php

declare(strict_types=1);

namespace App\Enums;

enum CarrierDriver: string
{
    case Manual = 'manual';
    case UpsSandbox = 'ups_sandbox';
    case FedexSandbox = 'fedex_sandbox';
    case OwnCourier = 'own_courier';

    public function label(): string
    {
        return match ($this) {
            self::Manual => 'Manual',
            self::UpsSandbox => 'UPS (sandbox)',
            self::FedexSandbox => 'FedEx (sandbox)',
            self::OwnCourier => 'Own courier',
        };
    }
}
