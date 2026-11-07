<?php

declare(strict_types=1);

namespace App\Http\Controllers\Resale;

use App\Enums\MarketplaceListingStatus;
use App\Http\Controllers\Controller;
use App\Models\MarketplaceListing;
use Illuminate\View\View;

final class ResaleStorefrontController extends Controller
{
    public function index(): View
    {
        $listings = MarketplaceListing::query()
            ->where('status', MarketplaceListingStatus::Published)
            ->with('product')
            ->latest('listed_at')
            ->paginate(12);

        return view('resale.index', [
            'listings' => $listings,
        ]);
    }

    public function show(MarketplaceListing $listing): View
    {
        abort_unless($listing->status === MarketplaceListingStatus::Published, 404);

        $listing->load('product');

        return view('resale.show', [
            'listing' => $listing,
        ]);
    }
}
