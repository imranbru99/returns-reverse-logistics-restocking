<?php

declare(strict_types=1);

namespace App\Enums;

enum ShipmentStatus: string
{
    case Draft = 'draft';
    case LabelCreated = 'label_created';
    case InTransit = 'in_transit';
    case OutForDelivery = 'out_for_delivery';
    case Delivered = 'delivered';
    case Exception = 'exception';
    case Returned = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::LabelCreated => 'Label created',
            self::InTransit => 'In transit',
            self::OutForDelivery => 'Out for delivery',
            self::Delivered => 'Delivered',
            self::Exception => 'Exception',
            self::Returned => 'Returned',
        };
    }
}
