<?php

declare(strict_types=1);

use App\Enums\DispositionType;
use App\Enums\Grade;
use App\Facades\Module;
use App\Models\Inspection;
use App\Models\Product;
use App\Models\Tenant;
use App\Services\Disposition\DispositionRouter;
use App\Support\TenantContext;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('suggests restock for grade A with healthy margin', function (): void {
    $tenant = Tenant::factory()->create();

    TenantContext::run($tenant, function () use ($tenant): void {
        $product = Product::factory()->for($tenant)->create([
            'meta' => ['margin_percent' => 40],
        ]);

        $inspection = Inspection::factory()->for($tenant)->create([
            'product_id' => $product->getKey(),
            'grade' => Grade::A,
        ]);

        $suggestion = app(DispositionRouter::class)->suggest($inspection, $product);

        expect($suggestion['type'])->toBe(DispositionType::RestockAsNew)
            ->and($suggestion['confidence'])->toBeGreaterThan(0.5);
    });
});

it('suggests refurbish for grade C', function (): void {
    $tenant = Tenant::factory()->create();

    TenantContext::run($tenant, function () use ($tenant): void {
        $inspection = Inspection::factory()->for($tenant)->create(['grade' => Grade::C]);
        $suggestion = app(DispositionRouter::class)->suggest($inspection);

        expect($suggestion['type'])->toBe(DispositionType::Refurbish);
    });
});
