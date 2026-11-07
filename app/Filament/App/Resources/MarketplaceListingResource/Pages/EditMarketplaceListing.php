<?php

declare(strict_types=1);

namespace App\Filament\App\Resources\MarketplaceListingResource\Pages;

use App\Filament\App\Resources\MarketplaceListingResource;
use Filament\Resources\Pages\EditRecord;

class EditMarketplaceListing extends EditRecord
{
    protected static string $resource = MarketplaceListingResource::class;
}
