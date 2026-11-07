<?php

declare(strict_types=1);

use App\Enums\RefundStatus;
use App\Facades\Module;
use App\Models\Order;
use App\Models\Rma;
use App\Models\StoreCreditAccount;
use App\Services\Refunds\ProcessRefundAction;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Config;

beforeEach(function (): void {
    Module::syncFromRegistry();
    Config::set('returns.demo_mode', true);
    Config::set('returns.sandbox.payments', true);
});

it('processes a partial refund to the original payment method in demo mode', function (): void {
    $rma = Rma::factory()->create();
    $order = Order::find($rma->order_id);
    $order?->forceFill(['meta' => ['payment_reference' => 'pi_test_123']])->save();

    TenantContext::run($rma->tenant, function () use ($rma): void {
        Module::for($rma->tenant)->enable('refunds-credits');

        $refund = app(ProcessRefundAction::class)->execute($rma, 25.00, 'original', 500);

        expect($refund->status)->toBe(RefundStatus::Completed)
            ->and((float) $refund->amount)->toBe(25.0)
            ->and($refund->meta['restocking_fee_cents'])->toBe(500)
            ->and($refund->meta['gateway'])->toBe('stripe_sandbox');
    });
});

it('issues store credit instead of a gateway refund', function (): void {
    $rma = Rma::factory()->create();

    TenantContext::run($rma->tenant, function () use ($rma): void {
        Module::for($rma->tenant)->enable('exchanges-store-credit');

        $refund = app(ProcessRefundAction::class)->execute($rma, 40.00, 'store_credit');

        $account = StoreCreditAccount::query()->where('customer_id', $rma->customer_id)->first();

        expect($refund->method)->toBe('store_credit')
            ->and($account?->balance_cents)->toBe(4000);
    });
});
