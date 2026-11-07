<?php

declare(strict_types=1);

use App\Facades\Module;
use App\Models\Tenant;
use App\Services\Modules\Exceptions\ModuleHasDependentsException;
use App\Support\TenantContext;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('auto-enables module dependencies when enabling disposition', function (): void {
    $tenant = Tenant::factory()->create();
    $manager = Module::for($tenant);

    $manager->enable('disposition-engine');

    expect($manager->enabled('disposition-engine'))->toBeTrue()
        ->and($manager->enabled('inspection-engine'))->toBeTrue()
        ->and($manager->enabled('inventory-management'))->toBeTrue();
});

it('blocks disabling inspection while disposition is enabled', function (): void {
    $tenant = Tenant::factory()->create();
    $manager = Module::for($tenant);

    $manager->enable('disposition-engine');

    expect(fn () => $manager->disable('inspection-engine'))
        ->toThrow(ModuleHasDependentsException::class);
});

it('reports module enabled state for the active tenant', function (): void {
    $tenant = Tenant::factory()->create();

    TenantContext::run($tenant, function (): void {
        expect(Module::enabled('tenant-organization'))->toBeTrue();
    });
});
