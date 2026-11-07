<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\MarketplaceListingResource\Pages;

use App\Filament\App\Resources\MarketplaceListingResource;
use Filament\Resources\Pages\ListRecords;

class ListMarketplaceListings extends ListRecords
{
    protected static string $resource = MarketplaceListingResource::class;
}
