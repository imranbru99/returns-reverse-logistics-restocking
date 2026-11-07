<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\WorkTaskResource\Pages;

use App\Filament\App\Resources\WorkTaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkTask extends CreateRecord
{
    protected static string $resource = WorkTaskResource::class;
}
