<?php

declare(strict_types=1);

use App\Enums\RmaStatus;
use App\Facades\Module;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Tenant;
use App\Services\Rma\CreateRmaAction;
use App\Support\TenantContext;

beforeEach(function (): void {
    Module::syncFromRegistry();
});

it('creates an RMA with line items from an order', function (): void {
    $tenant = Tenant::factory()->create();
    $action = app(CreateRmaAction::class);

    TenantContext::run($tenant, function () use ($tenant, $action): void {
        $product = Product::factory()->for($tenant)->create();
        $order = Order::factory()->for($tenant)->create();
        $item = OrderItem::factory()->for($tenant)->for($order)->create([
            'product_id' => $product->getKey(),
            'quantity' => 2,
        ]);

        $rma = $action->execute($order, [
            ['order_item_id' => $item->getKey(), 'quantity' => 1, 'reason' => 'Defective'],
        ], 'Changed mind');

        expect($rma->status)->toBe(RmaStatus::Requested)
            ->and($rma->number)->toStartWith('RMA-')
            ->and($rma->items)->toHaveCount(1)
            ->and($rma->items->first()?->quantity)->toBe(1)
            ->and($rma->statusHistories)->toHaveCount(1);
    });
});
