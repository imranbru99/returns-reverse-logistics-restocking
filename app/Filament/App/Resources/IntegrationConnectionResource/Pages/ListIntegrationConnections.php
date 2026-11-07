<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\IntegrationConnectionResource\Pages;

use App\Filament\App\Resources\IntegrationConnectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIntegrationConnections extends ListRecords
{
    protected static string $resource = IntegrationConnectionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
