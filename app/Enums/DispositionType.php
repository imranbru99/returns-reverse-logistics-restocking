<?php

declare(strict_types=1);

namespace App\Enums;

enum DispositionType: string
{
    case RestockAsNew = 'restock_as_new';
    case RestockOpenBox = 'restock_open_box';
    case Refurbish = 'refurbish';
    case Rtv = 'rtv';
    case Liquidate = 'liquidate';
    case Resell = 'resell';
    case Recycle = 'recycle';
    case Donate = 'donate';
    case Destroy = 'destroy';

    public function label(): string
    {
        return match ($this) {
            self::RestockAsNew => 'Restock as new',
            self::RestockOpenBox => 'Restock open box',
            self::Refurbish => 'Refurbish',
            self::Rtv => 'Return to vendor',
            self::Liquidate => 'Liquidate',
            self::Resell => 'Resell',
            self::Recycle => 'Recycle',
            self::Donate => 'Donate',
            self::Destroy => 'Destroy',
        };
    }
}
