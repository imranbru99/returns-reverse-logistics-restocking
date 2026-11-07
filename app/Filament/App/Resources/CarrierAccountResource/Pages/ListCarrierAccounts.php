<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\CarrierAccountResource\Pages;

use App\Filament\App\Resources\CarrierAccountResource;
use Filament\Resources\Pages\ListRecords;

class ListCarrierAccounts extends ListRecords
{
    protected static string $resource = CarrierAccountResource::class;
}
