<?php

declare(strict_types=1);

namespace App\Enums;

enum Grade: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case D = 'D';
    case Scrap = 'Scrap';

    public function label(): string
    {
        return match ($this) {
            self::A => 'Grade A — Like new',
            self::B => 'Grade B — Light use',
            self::C => 'Grade C — Moderate wear',
            self::D => 'Grade D — Heavy wear',
            self::Scrap => 'Scrap',
        };
    }
}
