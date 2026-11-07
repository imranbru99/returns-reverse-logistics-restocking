<?php

declare(strict_types=1);

use App\Enums\MarketplaceListingStatus;
use App\Facades\Module;
use App\Models\MarketplaceListing;
use App\Models\Product;
use App\Models\Tenant;
use App\Support\TenantContext;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('returns 404 for resale storefront when module is disabled', function (): void {
    $tenant = Tenant::factory()->create();
    $product = Product::factory()->for($tenant)->create();
    MarketplaceListing::query()->create([
        'tenant_id' => $tenant->getKey(),
        'product_id' => $product->getKey(),
        'price_cents' => 2500,
        'status' => MarketplaceListingStatus::Published,
        'listed_at' => now(),
    ]);

    TenantContext::run($tenant, function (): void {
        $this->get('/resale')->assertNotFound();
    });
});

it('renders resale listings when module is enabled', function (): void {
    $tenant = Tenant::factory()->create();
    $product = Product::factory()->for($tenant)->create();
    MarketplaceListing::query()->create([
        'tenant_id' => $tenant->getKey(),
        'product_id' => $product->getKey(),
        'grade' => 'A',
        'price_cents' => 2500,
        'currency' => 'USD',
        'status' => MarketplaceListingStatus::Published,
        'listed_at' => now(),
    ]);

    TenantContext::run($tenant, function (): void {
        Module::for(tenant())->enable('resale-marketplace');

        $this->get('/resale')->assertOk()->assertSee('Certified resale');
    });
});
