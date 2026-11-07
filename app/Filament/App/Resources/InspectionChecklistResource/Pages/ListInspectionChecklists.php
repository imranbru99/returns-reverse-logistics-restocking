<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\InspectionChecklistResource\Pages;

use App\Filament\App\Resources\InspectionChecklistResource;
use Filament\Resources\Pages\ListRecords;

class ListInspectionChecklists extends ListRecords
{
    protected static string $resource = InspectionChecklistResource::class;
}
