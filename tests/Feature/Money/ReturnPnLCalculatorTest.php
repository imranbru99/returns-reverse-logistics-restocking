<?php

declare(strict_types=1);

use App\Facades\Module;
use App\Models\ReturnCostLedger;
use App\Models\Rma;
use App\Services\Finance\ReturnPnLCalculator;
use App\Support\TenantContext;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('calculates return PnL from cost ledger', function (): void {
    $rma = Rma::factory()->create();
    ReturnCostLedger::query()->create([
        'tenant_id' => $rma->tenant_id,
        'rma_id' => $rma->getKey(),
        'shipping_cents' => 1000,
        'labor_cents' => 500,
        'parts_cents' => 200,
        'fees_cents' => 100,
        'recovery_cents' => 2500,
    ]);

    TenantContext::run($rma->tenant, function () use ($rma): void {
        Module::for($rma->tenant)->enable('finance-cost-accounting');

        $pnl = app(ReturnPnLCalculator::class)->forRma($rma);

        expect($pnl['costs_cents'])->toBe(1800)
            ->and($pnl['recovery_cents'])->toBe(2500)
            ->and($pnl['net_cents'])->toBe(700);
    });
});
