<?php

declare(strict_types=1);

use App\Enums\ExchangeOrderStatus;
use App\Facades\Module;
use App\Models\Rma;
use App\Services\Exchanges\CreateExchangeAction;
use App\Support\TenantContext;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('creates an exchange order linked to an RMA', function (): void {
    $rma = Rma::factory()->create();

    TenantContext::run($rma->tenant, function () use ($rma): void {
        Module::for($rma->tenant)->enable('exchanges-store-credit');

        $exchange = app(CreateExchangeAction::class)->execute($rma, [
            ['product_id' => '01test', 'quantity' => 1, 'sku' => 'SKU-1'],
        ]);

        expect($exchange->status)->toBe(ExchangeOrderStatus::Pending)
            ->and($exchange->rma_id)->toBe($rma->getKey())
            ->and($exchange->new_order_items)->toHaveCount(1);
    });
});
