<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\WorkTaskResource\Pages;

use App\Filament\App\Resources\WorkTaskResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkTasks extends ListRecords
{
    protected static string $resource = WorkTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
