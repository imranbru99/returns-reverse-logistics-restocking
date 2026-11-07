<?php

declare(strict_types=1);

namespace App\Enums;

enum MarketplaceOrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Shipped = 'shipped';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Paid => 'Paid',
            self::Shipped => 'Shipped',
            self::Cancelled => 'Cancelled',
        };
    }
}
