<?php

declare(strict_types=1);

namespace App\Services\Revenue;

use App\Enums\MarketplaceListingStatus;
use App\Models\MarketplaceListing;

final class PublishListingAction
{
    public function execute(MarketplaceListing $listing): MarketplaceListing
    {
        if ($listing->price_cents <= 0) {
            throw new \InvalidArgumentException('Listing price must be greater than zero.');
        }

        $listing->forceFill([
            'status' => MarketplaceListingStatus::Published,
            'listed_at' => now(),
        ])->save();

        return $listing->refresh();
    }
}
