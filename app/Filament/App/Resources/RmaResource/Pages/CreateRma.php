<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\RmaResource\Pages;

use App\Filament\App\Resources\RmaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;

class CreateRma extends CreateRecord
{
    protected static string $resource = RmaResource::class;

}
