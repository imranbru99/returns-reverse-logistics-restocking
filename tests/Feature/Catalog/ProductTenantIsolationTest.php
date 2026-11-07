<?php

declare(strict_types=1);

use App\Facades\Module;
use App\Models\Product;
use App\Models\Tenant;
use App\Support\TenantContext;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('scopes products to the active tenant context', function (): void {
    $tenantA = Tenant::factory()->create();
    $tenantB = Tenant::factory()->create();

    TenantContext::run($tenantA, function () use ($tenantA): void {
        Product::factory()->for($tenantA)->create(['name' => 'Tenant A Widget']);
    });

    TenantContext::run($tenantB, function (): void {
        expect(Product::query()->count())->toBe(0);
    });

    TenantContext::run($tenantA, function (): void {
        expect(Product::query()->count())->toBe(1);
        expect(Product::query()->first()?->name)->toBe('Tenant A Widget');
    });
});
