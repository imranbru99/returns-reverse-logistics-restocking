<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\RefurbishmentWorkOrderResource\Pages;

use App\Filament\App\Resources\RefurbishmentWorkOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListRefurbishmentWorkOrders extends ListRecords
{
    protected static string $resource = RefurbishmentWorkOrderResource::class;
}
