<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\MarketplaceListingResource\Pages;

use App\Filament\App\Resources\MarketplaceListingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMarketplaceListing extends CreateRecord
{
    protected static string $resource = MarketplaceListingResource::class;
}
