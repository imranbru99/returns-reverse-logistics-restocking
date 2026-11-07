<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentGatewayDriver: string
{
    case StripeSandbox = 'stripe_sandbox';
    case BkashSandbox = 'bkash_sandbox';
    case NagadSandbox = 'nagad_sandbox';
    case SslCommerzSandbox = 'sslcommerz_sandbox';

    public function label(): string
    {
        return match ($this) {
            self::StripeSandbox => 'Stripe Sandbox',
            self::BkashSandbox => 'Bkash Sandbox',
            self::NagadSandbox => 'Nagad Sandbox',
            self::SslCommerzSandbox => 'Ssl Commerz Sandbox',
        };
    }
}
