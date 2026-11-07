<?php

declare(strict_types=1);

namespace App\Enums;

enum MarketplaceListingStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Sold = 'sold';
    case Withdrawn = 'withdrawn';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
            self::Sold => 'Sold',
            self::Withdrawn => 'Withdrawn',
        };
    }
}
