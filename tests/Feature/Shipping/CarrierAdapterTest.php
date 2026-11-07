<?php

declare(strict_types=1);

use App\Facades\Module;
use App\Filament\App\Resources\ShipmentResource;
use App\Models\CarrierAccount;
use App\Models\Rma;
use App\Models\Tenant;
use App\Services\Shipping\Adapters\ManualCarrierAdapter;
use App\Support\TenantContext;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('rates and creates labels via the manual adapter', function (): void {
    $tenant = Tenant::factory()->create();

    TenantContext::run($tenant, function () use ($tenant): void {
        $account = CarrierAccount::factory()->for($tenant)->create();
        $rma = Rma::factory()->for($tenant)->create();
        $adapter = app(ManualCarrierAdapter::class);

        $rate = $adapter->rate($account, $rma);
        $label = $adapter->createLabel($account, $rma);
        $track = $adapter->track($account, $label['tracking_number']);

        expect($rate['rate_cents'])->toBeGreaterThan(0)
            ->and($label['tracking_number'])->toStartWith('MAN-')
            ->and($track['events'])->not->toBeEmpty();
    });
});

it('hides shipment resources when shipping-labels module is disabled', function (): void {
    $tenant = Tenant::factory()->create();

    TenantContext::run($tenant, function () use ($tenant): void {
        Module::for($tenant)->disable('shipping-labels', force: true);

        expect(Module::enabled('shipping-labels', $tenant))->toBeFalse()
            ->and(ShipmentResource::canAccess())->toBeFalse();
    });
});
