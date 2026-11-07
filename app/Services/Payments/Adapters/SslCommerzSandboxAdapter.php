<?php

declare(strict_types=1);

namespace App\Services\Payments\Adapters;

use App\Services\Payments\Contracts\PaymentGatewayAdapterInterface;
use Illuminate\Support\Str;

final class SslCommerzSandboxAdapter implements PaymentGatewayAdapterInterface
{
    public function refund(string $paymentReference, int $amountCents, string $currency, array $context = []): array
    {
        return [
            'success' => true,
            'reference' => 'SSL'.Str::upper(Str::random(12)),
            'meta' => [
                'sandbox' => true,
                'provider' => 'sslcommerz',
                'payment_reference' => $paymentReference,
                'amount_cents' => $amountCents,
                'currency' => $currency,
            ],
        ];
    }
}
