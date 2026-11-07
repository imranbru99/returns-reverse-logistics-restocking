<?php

declare(strict_types=1);

namespace App\Services\Payments\Adapters;

use App\Services\Payments\Contracts\PaymentGatewayAdapterInterface;
use Illuminate\Support\Str;

final class StripeSandboxAdapter implements PaymentGatewayAdapterInterface
{
    public function refund(string $paymentReference, int $amountCents, string $currency, array $context = []): array
    {
        return [
            'success' => true,
            'reference' => 're_'.Str::lower(Str::random(24)),
            'meta' => [
                'sandbox' => true,
                'provider' => 'stripe',
                'payment_reference' => $paymentReference,
                'amount_cents' => $amountCents,
                'currency' => $currency,
            ],
        ];
    }
}
