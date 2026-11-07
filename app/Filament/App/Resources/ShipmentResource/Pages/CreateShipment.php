<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\ShipmentResource\Pages;

use App\Filament\App\Resources\ShipmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShipment extends CreateRecord
{
    protected static string $resource = ShipmentResource::class;
}
