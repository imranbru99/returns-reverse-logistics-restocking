<?php

declare(strict_types=1);

namespace App\Services\Payments;

use App\Enums\PaymentGatewayDriver;
use App\Services\Payments\Adapters\BkashSandboxAdapter;
use App\Services\Payments\Adapters\NagadSandboxAdapter;
use App\Services\Payments\Adapters\SslCommerzSandboxAdapter;
use App\Services\Payments\Adapters\StripeSandboxAdapter;
use App\Services\Payments\Contracts\PaymentGatewayAdapterInterface;
use InvalidArgumentException;

final class PaymentGatewayManager
{
    public function resolve(PaymentGatewayDriver $driver): PaymentGatewayAdapterInterface
    {
        return match ($driver) {
            PaymentGatewayDriver::StripeSandbox => app(StripeSandboxAdapter::class),
            PaymentGatewayDriver::BkashSandbox => app(BkashSandboxAdapter::class),
            PaymentGatewayDriver::NagadSandbox => app(NagadSandboxAdapter::class),
            PaymentGatewayDriver::SslCommerzSandbox => app(SslCommerzSandboxAdapter::class),
        };
    }

    public function defaultDriver(): PaymentGatewayDriver
    {
        if ((bool) config('returns.sandbox.stripe')) {
            return PaymentGatewayDriver::StripeSandbox;
        }

        if ((bool) config('returns.sandbox.bkash')) {
            return PaymentGatewayDriver::BkashSandbox;
        }

        throw new InvalidArgumentException('No payment sandbox driver enabled.');
    }
}
