<?php

declare(strict_types=1);

namespace App\Enums;

enum WarrantyClaimStatus: string
{
    case Submitted = 'submitted';
    case InReview = 'in_review';
    case Approved = 'approved';
    case Denied = 'denied';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Submitted',
            self::InReview => 'In Review',
            self::Approved => 'Approved',
            self::Denied => 'Denied',
            self::Closed => 'Closed',
        };
    }
}
