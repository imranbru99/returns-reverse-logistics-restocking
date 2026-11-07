<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Facades\Module;
use App\Models\Rma;
use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantContext;
use Database\Seeders\RolePermissionSeeder;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    Module::syncFromRegistry();
    $this->seed(RolePermissionSeeder::class);
});

it('requires authentication for rma index', function (): void {
    $tenant = Tenant::factory()->create();

    $this->getJson('/api/v1/rmas', ['X-Tenant-ID' => $tenant->getKey()])
        ->assertUnauthorized();
});

it('lists rmas for an authenticated tenant user', function (): void {
    $tenant = Tenant::factory()->create();
    Module::for($tenant)->enable('rma-management');

    $user = User::factory()->create(['user_type' => UserType::TenantOwner]);
    $user->tenants()->attach($tenant);

    TenantContext::run($tenant, function () use ($tenant): void {
        Rma::factory()->count(2)->create(['tenant_id' => $tenant->getKey()]);
    });

    Sanctum::actingAs($user);

    $this->getJson('/api/v1/rmas', ['X-Tenant-ID' => $tenant->getKey()])
        ->assertOk()
        ->assertJsonPath('total', 2);
});
