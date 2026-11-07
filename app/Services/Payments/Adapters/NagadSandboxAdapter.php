<?php

declare(strict_types=1);

namespace App\Services\Payments\Adapters;

use App\Services\Payments\Contracts\PaymentGatewayAdapterInterface;
use Illuminate\Support\Str;

final class NagadSandboxAdapter implements PaymentGatewayAdapterInterface
{
    public function refund(string $paymentReference, int $amountCents, string $currency, array $context = []): array
    {
        return [
            'success' => true,
            'reference' => 'NG'.Str::upper(Str::random(10)),
            'meta' => [
                'sandbox' => true,
                'provider' => 'nagad',
                'payment_reference' => $paymentReference,
                'amount_cents' => $amountCents,
                'currency' => $currency,
            ],
        ];
    }
}
