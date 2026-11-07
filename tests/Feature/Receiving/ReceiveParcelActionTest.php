<?php

declare(strict_types=1);

use App\Enums\RmaStatus;
use App\Facades\Module;
use App\Models\Rma;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\Receiving\ReceiveParcelAction;
use App\Support\TenantContext;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('creates a receiving record and transitions the RMA to received', function (): void {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create();

    TenantContext::run($tenant, function () use ($tenant, $user): void {
        $warehouse = Warehouse::factory()->for($tenant)->create(['name' => 'Main DC']);
        $rma = Rma::factory()->for($tenant)->create(['status' => RmaStatus::InTransit]);

        $record = app(ReceiveParcelAction::class)->execute(
            warehouse: $warehouse,
            receiver: $user,
            rma: $rma,
            expectedQty: 2,
            receivedQty: 2,
        );

        expect($record->received_qty)->toBe(2)
            ->and($record->warehouse_id)->toBe($warehouse->getKey())
            ->and($rma->fresh()->status)->toBe(RmaStatus::Received);
    });
});
