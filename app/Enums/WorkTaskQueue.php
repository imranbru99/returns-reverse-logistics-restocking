<?php

declare(strict_types=1);

namespace App\Enums;

enum WorkTaskQueue: string
{
    case Receiving = 'receiving';
    case Inspection = 'inspection';
    case Putaway = 'putaway';
    case Refurb = 'refurb';

    public function label(): string
    {
        return match ($this) {
            self::Receiving => 'Receiving',
            self::Inspection => 'Inspection',
            self::Putaway => 'Putaway',
            self::Refurb => 'Refurb',
        };
    }
}
