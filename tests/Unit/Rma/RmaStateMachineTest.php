<?php

declare(strict_types=1);

use App\Enums\RmaStatus;
use App\Models\Rma;
use App\Models\Tenant;
use App\Services\Rma\RmaStateMachine;
use App\Support\TenantContext;

it('allows valid RMA status transitions and records history', function (): void {
    $tenant = Tenant::factory()->create();
    $machine = app(RmaStateMachine::class);

    TenantContext::run($tenant, function () use ($tenant, $machine): void {
        $rma = Rma::factory()->for($tenant)->create(['status' => RmaStatus::Requested]);

        $machine->transition($rma, RmaStatus::Approved);

        $rma->refresh();
        expect($rma->status)->toBe(RmaStatus::Approved)
            ->and($rma->statusHistories()->count())->toBe(1)
            ->and($rma->statusHistories()->first()?->to_status)->toBe(RmaStatus::Approved);
    });
});

it('rejects invalid RMA status transitions', function (): void {
    $tenant = Tenant::factory()->create();
    $machine = app(RmaStateMachine::class);

    TenantContext::run($tenant, function () use ($tenant, $machine): void {
        $rma = Rma::factory()->for($tenant)->create(['status' => RmaStatus::Requested]);

        expect(fn () => $machine->transition($rma, RmaStatus::Closed))
            ->toThrow(InvalidArgumentException::class);
    });
});

it('exposes transition rules on the RmaStatus enum', function (): void {
    expect(RmaStatus::Requested->canTransitionTo(RmaStatus::Approved))->toBeTrue()
        ->and(RmaStatus::Requested->canTransitionTo(RmaStatus::Closed))->toBeFalse();
});
