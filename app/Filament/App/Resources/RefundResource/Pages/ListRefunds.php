<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\RefundResource\Pages;

use App\Filament\App\Resources\RefundResource;
use Filament\Resources\Pages\ListRecords;

class ListRefunds extends ListRecords
{
    protected static string $resource = RefundResource::class;
}
