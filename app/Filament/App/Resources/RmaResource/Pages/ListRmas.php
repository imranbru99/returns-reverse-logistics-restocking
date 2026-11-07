<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\RmaResource\Pages;

use App\Filament\App\Resources\RmaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRmas extends ListRecords
{
    protected static string $resource = RmaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
