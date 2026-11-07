<?php

declare(strict_types=1);

use App\Facades\Module;
use App\Models\Inspection;
use App\Models\Tenant;
use App\Services\Inspection\AiInspectionAssistService;
use App\Support\TenantContext;
use ImranDevBd\AiHub\Facades\AIHub;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('returns structured AI assist without auto applying by default', function (): void {
    AIHub::fake([
        '{"damage_detected":true,"damage_summary":"Corner dent","suggested_grade":"B","confidence":0.87,"inspection_summary":"Minor cosmetic damage"}',
    ]);

    $tenant = Tenant::factory()->create();

    TenantContext::run($tenant, function () use ($tenant): void {
        $inspection = Inspection::factory()->for($tenant)->create([
            'notes' => 'Box crushed on one side',
            'checklist' => ['responses' => [['key' => 'visual', 'pass' => false]]],
        ]);

        $service = app(AiInspectionAssistService::class);
        $result = $service->analyze($inspection, [], humanOverride: false);

        expect($result['damage_detected'])->toBeTrue()
            ->and($result['suggested_grade'])->toBe('B')
            ->and($result['confidence'])->toBeGreaterThan(0.8)
            ->and($result['auto_applied'])->toBeFalse()
            ->and($inspection->fresh()->meta)->toBeNull();
    });
});

it('stores assist metadata when human override is enabled', function (): void {
    AIHub::fake(['{"damage_detected":false,"damage_summary":"","suggested_grade":"A","confidence":0.95,"inspection_summary":"Looks good"}']);

    $tenant = Tenant::factory()->create();

    TenantContext::run($tenant, function () use ($tenant): void {
        $inspection = Inspection::factory()->for($tenant)->create();

        $result = app(AiInspectionAssistService::class)->analyze($inspection, [], humanOverride: true);

        expect($result['auto_applied'])->toBeTrue()
            ->and($inspection->fresh()->meta)->toHaveKey('ai_assist');
    });
});
