<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\InspectionResource\Pages;

use App\Filament\App\Resources\InspectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;

class CreateInspection extends CreateRecord
{
    protected static string $resource = InspectionResource::class;

}
