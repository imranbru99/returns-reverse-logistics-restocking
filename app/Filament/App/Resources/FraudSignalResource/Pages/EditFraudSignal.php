<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\FraudSignalResource\Pages;

use App\Filament\App\Resources\FraudSignalResource;
use Filament\Resources\Pages\EditRecord;

class EditFraudSignal extends EditRecord
{
    protected static string $resource = FraudSignalResource::class;
}
