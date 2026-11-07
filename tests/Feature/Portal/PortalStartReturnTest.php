<?php

declare(strict_types=1);

use App\Enums\UserType;
use App\Facades\Module;
use App\Livewire\Portal\StartReturn;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Tenant;
use App\Support\TenantContext;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function (): void {
    Module::syncFromRegistry();
    $this->seed(RolePermissionSeeder::class);
});

it('creates an RMA from the customer portal wizard', function (): void {
    $tenant = Tenant::factory()->create();
    Module::for($tenant)->enable('rma-management');
    Module::for($tenant)->enable('orders-customers');

    TenantContext::run($tenant, function () use ($tenant): void {
        $customer = Customer::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'email' => 'shopper@example.com',
        ]);
        $product = Product::factory()->create(['tenant_id' => $tenant->getKey()]);
        $order = Order::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'customer_id' => $customer->getKey(),
            'number' => 'ORD-PORTAL-1',
        ]);
        OrderItem::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'order_id' => $order->getKey(),
            'product_id' => $product->getKey(),
        ]);

        Livewire::test(StartReturn::class)
            ->set('orderNumber', 'ORD-PORTAL-1')
            ->set('email', 'shopper@example.com')
            ->call('lookupOrder')
            ->assertSet('step', 2)
            ->set('selectedItems.'.$order->items()->first()->getKey(), true)
            ->set('reason', 'Wrong size')
            ->call('submit')
            ->assertSet('step', 4);

        expect($order->fresh()->rmas)->toHaveCount(1);
    });
});
