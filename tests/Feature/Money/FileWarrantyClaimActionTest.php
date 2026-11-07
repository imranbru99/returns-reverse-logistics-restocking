<?php

declare(strict_types=1);

use App\Enums\WarrantyClaimStatus;
use App\Facades\Module;
use App\Models\Customer;
use App\Models\Product;
use App\Models\WarrantyRegistration;
use App\Services\Warranty\FileWarrantyClaimAction;
use App\Support\TenantContext;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('files a warranty claim against a registration', function (): void {
    $product = Product::factory()->create();
    $customer = Customer::factory()->for($product->tenant)->create();
    $registration = WarrantyRegistration::query()->create([
        'tenant_id' => $product->tenant_id,
        'customer_id' => $customer->getKey(),
        'product_id' => $product->getKey(),
    ]);

    TenantContext::run($product->tenant, function () use ($registration): void {
        Module::for($registration->tenant)->enable('warranty-claims');

        $claim = app(FileWarrantyClaimAction::class)->execute($registration, 'Unit stopped charging');

        expect($claim->status)->toBe(WarrantyClaimStatus::Submitted)
            ->and($claim->number)->toStartWith('WCL-');
    });
});
