<?php

declare(strict_types=1);

namespace App\Enums;

enum GiftCardStatus: string
{
    case Active = 'active';
    case Redeemed = 'redeemed';
    case Expired = 'expired';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Redeemed => 'Redeemed',
            self::Expired => 'Expired',
            self::Cancelled => 'Cancelled',
        };
    }
}
