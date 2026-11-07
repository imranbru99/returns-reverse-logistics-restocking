<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Facades\Module;
use App\Filament\App\Resources\CustomerResource;
use App\Filament\App\Resources\MarketplaceListingResource;
use App\Filament\App\Resources\ProductResource;
use App\Filament\App\Resources\StoreCreditAccountResource;
use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantContext;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function (): void {
    Module::syncFromRegistry();
    $this->seed(RolePermissionSeeder::class);
});

it('hides catalog resources when the product-catalog module is disabled', function (): void {
    $tenant = Tenant::factory()->create();

    TenantContext::run($tenant, function () use ($tenant): void {
        Module::for($tenant)->disable('product-catalog', force: true);

        expect(ProductResource::canAccess())->toBeFalse();
    });
});

it('hides marketplace listings when resale-marketplace is disabled', function (): void {
    $tenant = Tenant::factory()->create();

    TenantContext::run($tenant, function () use ($tenant): void {
        Module::for($tenant)->disable('resale-marketplace', force: true);

        expect(MarketplaceListingResource::canAccess())->toBeFalse();
    });
});

it('allows store credit resources when exchanges-store-credit is enabled', function (): void {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['user_type' => UserType::TenantOwner]);
    $user->tenants()->attach($tenant);
    $this->actingAs($user);

    TenantContext::run($tenant, function () use ($tenant): void {
        setPermissionsTeamId($tenant->getKey());
        auth()->user()?->assignRole(UserType::TenantOwner->value);
        Module::for($tenant)->enable('exchanges-store-credit');

        expect(StoreCreditAccountResource::canAccess())->toBeTrue();
    });
});

it('allows customer resources when orders-customers is enabled for the tenant', function (): void {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['user_type' => UserType::TenantOwner]);
    $user->tenants()->attach($tenant);

    $this->actingAs($user);

    TenantContext::run($tenant, function () use ($tenant): void {
        setPermissionsTeamId($tenant->getKey());
        $user = auth()->user();
        $user?->assignRole(UserType::TenantOwner->value);

        Module::for($tenant)->enable('orders-customers');

        expect(CustomerResource::canAccess())->toBeTrue();
    });
});
