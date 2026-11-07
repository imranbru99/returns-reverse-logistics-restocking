<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\VendorClaimResource\Pages;

use App\Filament\App\Resources\VendorClaimResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorClaim extends CreateRecord
{
    protected static string $resource = VendorClaimResource::class;
}
