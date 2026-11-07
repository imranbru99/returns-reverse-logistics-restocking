<?php

declare(strict_types=1);

namespace App\Services\Refunds;

use App\Enums\PaymentGatewayDriver;
use App\Enums\RefundStatus;
use App\Models\Refund;
use App\Models\Rma;
use App\Services\Payments\PaymentGatewayManager;
use App\Services\StoreCredit\CreditWalletService;
use App\Support\NumberGenerator;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class ProcessRefundAction
{
    public function __construct(
        private readonly NumberGenerator $numbers,
        private readonly PaymentGatewayManager $gateways,
        private readonly CreditWalletService $creditWallet,
    ) {}

    /**
     * @param  'original'|'store_credit'  $method
     */
    public function execute(
        Rma $rma,
        float $amount,
        string $method = 'original',
        int $restockingFeeCents = 0,
        ?PaymentGatewayDriver $gateway = null,
    ): Refund {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Refund amount must be positive.');
        }

        $tenantId = (string) $rma->tenant_id;
        $currency = (string) ($rma->order?->currency ?? config('returns.currency.default', 'USD'));
        $grossCents = (int) round($amount * 100);
        $netCents = max(0, $grossCents - $restockingFeeCents);

        return DB::transaction(function () use ($rma, $method, $restockingFeeCents, $tenantId, $currency, $amount, $netCents, $gateway): Refund {
            $number = $this->numbers->next($tenantId, 'refund', 'REF');

            $refund = Refund::query()->create([
                'tenant_id' => $tenantId,
                'rma_id' => $rma->getKey(),
                'order_id' => $rma->order_id,
                'number' => $number,
                'status' => RefundStatus::Processing,
                'amount' => $amount,
                'currency' => $currency,
                'method' => $method,
                'meta' => [
                    'restocking_fee_cents' => $restockingFeeCents,
                    'net_cents' => $netCents,
                    'partial' => $amount < (float) ($rma->order?->total ?? $amount),
                ],
            ]);

            if ($method === 'store_credit') {
                $customerId = $rma->customer_id ?? $rma->order?->customer_id;
                if ($customerId === null) {
                    throw new InvalidArgumentException('Store credit refund requires a customer.');
                }
                $this->creditWallet->credit((string) $customerId, $netCents, $currency, $refund);
            } else {
                $this->refundToOriginalMethod($refund, $netCents, $currency, $gateway);
            }

            $refund->forceFill([
                'status' => RefundStatus::Completed,
                'processed_at' => now(),
            ])->save();

            return $refund->refresh();
        });
    }

    private function refundToOriginalMethod(Refund $refund, int $netCents, string $currency, ?PaymentGatewayDriver $gateway): void
    {
        $sandbox = (bool) config('returns.demo_mode') || (bool) config('returns.sandbox.payments');

        if (! $sandbox) {
            throw new InvalidArgumentException('Live payment refunds are not configured in this environment.');
        }

        $driver = $gateway ?? $this->gateways->defaultDriver();
        $paymentReference = (string) ($refund->order?->meta['payment_reference'] ?? 'pay_unknown');
        $adapter = $this->gateways->resolve($driver);
        $result = $adapter->refund($paymentReference, $netCents, $currency, [
            'refund_id' => $refund->getKey(),
        ]);

        if (! ($result['success'] ?? false)) {
            throw new InvalidArgumentException('Payment gateway refund failed.');
        }

        $refund->forceFill([
            'meta' => array_merge($refund->meta ?? [], [
                'gateway' => $driver->value,
                'gateway_result' => $result,
            ]),
        ])->save();
    }
}
