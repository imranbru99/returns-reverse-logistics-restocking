<?php

declare(strict_types=1);

use App\Enums\RmaStatus;
use App\Enums\ShipmentStatus;
use App\Facades\Module;
use App\Models\CarrierAccount;
use App\Models\Rma;
use App\Models\Tenant;
use App\Services\Shipping\CreateReturnLabelAction;
use App\Support\TenantContext;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

beforeEach(function (): void {
    Module::syncFromRegistry();
    config(['returns.sandbox.carriers' => true]);
});

it('creates a shipment and logs label in sandbox mode', function (): void {
    $tenant = Tenant::factory()->create();

    TenantContext::run($tenant, function () use ($tenant): void {
        CarrierAccount::factory()->for($tenant)->create(['is_default' => true]);
        $rma = Rma::factory()->for($tenant)->create(['status' => RmaStatus::Approved]);

        $shipment = app(CreateReturnLabelAction::class)->execute($rma);

        expect($shipment->status)->toBe(ShipmentStatus::LabelCreated)
            ->and($shipment->tracking_number)->not->toBeEmpty()
            ->and($shipment->label_path)->toContain('labels/demo')
            ->and($rma->fresh()->status)->toBe(RmaStatus::LabelIssued);
    });
});

it('can generate PDF labels with UnicodePdf fake when sandbox is off', function (): void {
    $pdfFake = UnicodePdf::fake();
    config(['returns.sandbox.carriers' => false, 'returns.demo_mode' => false]);

    $tenant = Tenant::factory()->create();

    TenantContext::run($tenant, function () use ($tenant, $pdfFake): void {
        CarrierAccount::factory()->for($tenant)->create([
            'is_default' => true,
            'sandbox' => false,
        ]);
        $rma = Rma::factory()->for($tenant)->create(['status' => RmaStatus::Approved]);

        $shipment = app(CreateReturnLabelAction::class)->execute($rma);

        expect($shipment->label_path)->not->toBeNull();
        $pdfFake->assertGenerated(1);
    });
});
