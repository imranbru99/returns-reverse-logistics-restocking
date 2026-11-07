<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\RmaResource\Pages;

use App\Filament\App\Resources\RmaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\EditRecord;

class EditRma extends EditRecord
{
    protected static string $resource = RmaResource::class;

}
