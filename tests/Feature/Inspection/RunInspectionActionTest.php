<?php

declare(strict_types=1);

use App\Enums\Grade;
use App\Enums\InspectionStatus;
use App\Enums\RmaStatus;
use App\Facades\Module;
use App\Models\InspectionChecklist;
use App\Models\Rma;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Inspection\RunInspectionAction;
use App\Support\TenantContext;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('starts and completes an inspection with scoring and grade', function (): void {
    $tenant = Tenant::factory()->create();
    $inspector = User::factory()->create();

    TenantContext::run($tenant, function () use ($tenant, $inspector): void {
        $checklist = InspectionChecklist::factory()->for($tenant)->create();
        $rma = Rma::factory()->for($tenant)->create(['status' => RmaStatus::Received]);

        $action = app(RunInspectionAction::class);

        $inspection = $action->start(
            checklist: $checklist,
            inspector: $inspector,
            rmaId: $rma->getKey(),
        );

        expect($inspection->status)->toBe(InspectionStatus::InProgress)
            ->and($inspection->started_at)->not->toBeNull()
            ->and($rma->fresh()->status)->toBe(RmaStatus::Inspecting);

        $responses = [
            ['key' => 'visual', 'pass' => true],
            ['key' => 'functional', 'pass' => true],
        ];

        $completed = $action->complete($inspection, $responses, ['photo_front' => 'x'], $inspector);

        expect($completed->status)->toBe(InspectionStatus::Completed)
            ->and($completed->grade)->toBe(Grade::A)
            ->and($completed->completed_at)->not->toBeNull()
            ->and($rma->fresh()->status)->toBe(RmaStatus::Inspected);
    });
});
