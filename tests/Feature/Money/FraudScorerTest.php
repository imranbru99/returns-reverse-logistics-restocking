<?php

declare(strict_types=1);

use App\Facades\Module;
use App\Models\Customer;
use App\Models\Rma;
use App\Services\Fraud\FraudScorer;
use App\Support\TenantContext;
use ImranDevBd\AiHub\Facades\AIHub;

beforeEach(function (): void {
    Module::syncFromRegistry();
    AIHub::fake(['5']);
});

it('scores fraud signals from return patterns', function (): void {
    $customer = Customer::factory()->create();
    Rma::withoutEvents(function () use ($customer): void {
        Rma::factory()->count(6)->for($customer->tenant)->create(['customer_id' => $customer->getKey()]);
    });
    $rma = Rma::factory()->for($customer->tenant)->create(['customer_id' => $customer->getKey()]);

    TenantContext::run($customer->tenant, function () use ($customer, $rma): void {
        Module::for($customer->tenant)->enable('fraud-abuse-prevention');

        $result = app(FraudScorer::class)->score($customer, null, $rma);

        expect($result['score'])->toBeGreaterThanOrEqual(25)
            ->and($result['signals'])->toContain('high_return_velocity')
            ->and($result['fraud_signal']->rma_id)->toBe($rma->getKey());
    });
});
