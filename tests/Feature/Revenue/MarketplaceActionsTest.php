<?php

declare(strict_types=1);

use App\Enums\AuctionStatus;
use App\Enums\MarketplaceListingStatus;
use App\Facades\Module;
use App\Models\Auction;
use App\Enums\LiquidationLotStatus;
use App\Models\LiquidationLot;
use App\Models\MarketplaceListing;
use App\Models\Product;
use App\Models\RecyclingPartner;
use App\Services\Revenue\IssueDestructionCertificateAction;
use App\Services\Revenue\PlaceAuctionBidAction;
use App\Services\Revenue\PublishListingAction;
use App\Support\TenantContext;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

beforeEach(function (): void {
    Module::syncFromRegistry();
    UnicodePdf::fake();
});

it('publishes a marketplace listing', function (): void {
    $product = Product::factory()->create();
    $listing = MarketplaceListing::query()->create([
        'tenant_id' => $product->tenant_id,
        'product_id' => $product->getKey(),
        'grade' => 'B',
        'price_cents' => 4999,
        'currency' => 'USD',
        'status' => MarketplaceListingStatus::Draft,
    ]);

    TenantContext::run($product->tenant, function () use ($listing): void {
        Module::for($listing->tenant)->enable('resale-marketplace');

        $published = app(PublishListingAction::class)->execute($listing);

        expect($published->status)->toBe(MarketplaceListingStatus::Published)
            ->and($published->listed_at)->not->toBeNull();
    });
});

it('places an auction bid above the current winning amount', function (): void {
    $lot = LiquidationLot::query()->create([
        'tenant_id' => \App\Models\Tenant::factory()->create()->getKey(),
        'title' => 'Salvage lot',
        'status' => LiquidationLotStatus::Listed,
        'reserve_price_cents' => 10000,
    ]);
    $auction = Auction::query()->create([
        'tenant_id' => $lot->tenant_id,
        'liquidation_lot_id' => $lot->getKey(),
        'starts_at' => now()->subHour(),
        'ends_at' => now()->addDay(),
        'status' => AuctionStatus::Scheduled,
        'winning_bid_cents' => 5000,
    ]);

    TenantContext::run($lot->tenant, function () use ($auction): void {
        Module::for($auction->tenant)->enable('liquidation-auctions');

        $bid = app(PlaceAuctionBidAction::class)->execute($auction, 'Bidder One', 7500);

        expect($bid->amount_cents)->toBe(7500)
            ->and($auction->refresh()->winning_bid_cents)->toBe(7500);
    });
});

it('issues a destruction certificate PDF', function (): void {
    $partner = RecyclingPartner::query()->create([
        'tenant_id' => \App\Models\Tenant::factory()->create()->getKey(),
        'name' => 'EcoRecycle',
    ]);

    TenantContext::run($partner->tenant, function () use ($partner): void {
        Module::for($partner->tenant)->enable('recycling-sustainability');

        $cert = app(IssueDestructionCertificateAction::class)->execute($partner, [
            ['description' => 'Laptop chassis', 'quantity' => 12],
        ]);

        expect($cert->pdf_path)->not->toBeNull()
            ->and($cert->certificate_number)->toStartWith('DSC-');
    });
});
