<?php

declare(strict_types=1);

use App\Enums\DispositionType;
use App\Enums\Grade;
use App\Models\Disposition;
use App\Models\Inspection;
use App\Models\Product;
use App\Models\Rma;
use App\Models\RmaItem;
use App\Models\Tenant;
use App\Services\Analytics\ReturnsAnalyticsService;
use App\Support\TenantContext;

it('aggregates return, inspection, and recovery metrics', function (): void {
    $tenant = Tenant::factory()->create();
    $product = Product::factory()->create(['tenant_id' => $tenant->getKey()]);

    TenantContext::run($tenant, function () use ($tenant, $product): void {
        $rma = Rma::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'reason' => 'Defective',
        ]);

        RmaItem::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'rma_id' => $rma->getKey(),
            'product_id' => $product->getKey(),
            'quantity' => 2,
        ]);

        $inspection = Inspection::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'rma_id' => $rma->getKey(),
            'product_id' => $product->getKey(),
            'grade' => Grade::B,
            'completed_at' => now(),
        ]);

        Disposition::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'inspection_id' => $inspection->getKey(),
            'product_id' => $product->getKey(),
            'type' => DispositionType::RestockAsNew,
            'quantity' => 1,
        ]);

        Disposition::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'inspection_id' => $inspection->getKey(),
            'product_id' => $product->getKey(),
            'type' => DispositionType::Destroy,
            'quantity' => 1,
        ]);

        $analytics = ReturnsAnalyticsService::for($tenant);

        expect($analytics->returnRateByReason()->first()['reason'])->toBe('Defective')
            ->and($analytics->inspectionOutcomes()['total'])->toBe(1)
            ->and($analytics->recoveryRate())->toBe(0.5);
    });
});
