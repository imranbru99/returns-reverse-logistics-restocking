<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\FraudSignalResource\Pages;

use App\Filament\App\Resources\FraudSignalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFraudSignal extends CreateRecord
{
    protected static string $resource = FraudSignalResource::class;
}
