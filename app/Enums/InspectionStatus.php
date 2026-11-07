<?php

declare(strict_types=1);

namespace App\Enums;

enum InspectionStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case AwaitingReview = 'awaiting_review';
    case Completed = 'completed';
    case Escalated = 'escalated';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::InProgress => 'In progress',
            self::AwaitingReview => 'Awaiting review',
            self::Completed => 'Completed',
            self::Escalated => 'Escalated',
        };
    }
}
