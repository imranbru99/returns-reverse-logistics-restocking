<?php

declare(strict_types=1);

namespace App\Enums;

enum StoreCreditLedgerType: string
{
    case Credit = 'credit';
    case Debit = 'debit';
    case Adjustment = 'adjustment';

    public function label(): string
    {
        return match ($this) {
            self::Credit => 'Credit',
            self::Debit => 'Debit',
            self::Adjustment => 'Adjustment',
        };
    }
}
