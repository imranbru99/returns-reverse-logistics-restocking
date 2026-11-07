<?php

declare(strict_types=1);

namespace App\Enums;

enum RmaStatus: string
{
    case Requested = 'requested';
    case Approved = 'approved';
    case LabelIssued = 'label_issued';
    case InTransit = 'in_transit';
    case Received = 'received';
    case Inspecting = 'inspecting';
    case Inspected = 'inspected';
    case Dispositioned = 'dispositioned';
    case Resolved = 'resolved';
    case Closed = 'closed';
    case Cancelled = 'cancelled';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Requested => 'Requested',
            self::Approved => 'Approved',
            self::LabelIssued => 'Label issued',
            self::InTransit => 'In transit',
            self::Received => 'Received',
            self::Inspecting => 'Inspecting',
            self::Inspected => 'Inspected',
            self::Dispositioned => 'Dispositioned',
            self::Resolved => 'Resolved',
            self::Closed => 'Closed',
            self::Cancelled => 'Cancelled',
            self::Rejected => 'Rejected',
        };
    }

    /**
     * @return list<RmaStatus>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Requested => [self::Approved, self::Rejected, self::Cancelled],
            self::Approved => [self::LabelIssued, self::Cancelled],
            self::LabelIssued => [self::InTransit, self::Cancelled],
            self::InTransit => [self::Received, self::Cancelled],
            self::Received => [self::Inspecting, self::Cancelled],
            self::Inspecting => [self::Inspected, self::Cancelled],
            self::Inspected => [self::Dispositioned],
            self::Dispositioned => [self::Resolved],
            self::Resolved => [self::Closed],
            self::Closed, self::Cancelled, self::Rejected => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }
}
