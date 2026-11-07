<?php

declare(strict_types=1);

namespace App\Enums;

enum LiquidationLotStatus: string
{
    case Draft = 'draft';
    case Listed = 'listed';
    case Sold = 'sold';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Listed => 'Listed',
            self::Sold => 'Sold',
            self::Archived => 'Archived',
        };
    }
}
