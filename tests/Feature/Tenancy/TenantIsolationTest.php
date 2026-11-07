<?php

declare(strict_types=1);

use App\Facades\Module;
use App\Models\Location;
use App\Models\Tenant;
use App\Support\TenantContext;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('scopes locations to the active tenant context', function (): void {
    $tenantA = Tenant::factory()->create();
    $tenantB = Tenant::factory()->create();

    TenantContext::run($tenantA, function () use ($tenantA): void {
        Location::factory()->for($tenantA)->create(['name' => 'Tenant A Dock']);
    });

    TenantContext::run($tenantB, function (): void {
        expect(Location::query()->count())->toBe(0);
    });

    TenantContext::run($tenantA, function (): void {
        expect(Location::query()->count())->toBe(1);
        expect(Location::query()->first()?->name)->toBe('Tenant A Dock');
    });
});
