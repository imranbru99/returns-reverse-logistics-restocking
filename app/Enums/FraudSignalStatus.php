<?php

declare(strict_types=1);

namespace App\Enums;

enum FraudSignalStatus: string
{
    case Open = 'open';
    case Reviewing = 'reviewing';
    case Cleared = 'cleared';
    case Blocked = 'blocked';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Reviewing => 'Reviewing',
            self::Cleared => 'Cleared',
            self::Blocked => 'Blocked',
        };
    }
}
