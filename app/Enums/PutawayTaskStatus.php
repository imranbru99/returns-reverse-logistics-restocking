<?php

declare(strict_types=1);

namespace App\Enums;

enum PutawayTaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Done = 'done';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::InProgress => 'In progress',
            self::Done => 'Done',
        };
    }
}
