<?php

declare(strict_types=1);

namespace App\Enums;

enum AuctionStatus: string
{
    case Scheduled = 'scheduled';
    case Live = 'live';
    case Closed = 'closed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Scheduled => 'Scheduled',
            self::Live => 'Live',
            self::Closed => 'Closed',
            self::Cancelled => 'Cancelled',
        };
    }
}
