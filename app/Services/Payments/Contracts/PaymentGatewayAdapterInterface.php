<?php

declare(strict_types=1);

namespace App\Services\Payments\Contracts;

/**
 * @phpstan-type RefundResult array{success: bool, reference?: string, meta?: array<string, mixed>}
 */
interface PaymentGatewayAdapterInterface
{
    /**
     * @param  array<string, mixed>  $context
     * @return RefundResult
     */
    public function refund(string $paymentReference, int $amountCents, string $currency, array $context = []): array;
}
