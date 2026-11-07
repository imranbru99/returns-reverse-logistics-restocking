<?php

declare(strict_types=1);

use App\Models\Tenant;
use App\Services\Modules\ModuleManager;
use App\Support\TenantContext;

if (! function_exists('tenant')) {
    function tenant(): ?Tenant
    {
        return TenantContext::get();
    }
}

if (! function_exists('tenant_id')) {
    function tenant_id(): ?string
    {
        return TenantContext::id();
    }
}

if (! function_exists('module')) {
    function module(?string $key = null): ModuleManager|bool
    {
        /** @var ModuleManager $manager */
        $manager = app(ModuleManager::class);

        if ($key === null) {
            return $manager;
        }

        return $manager->enabled($key);
    }
}

if (! function_exists('money')) {
    /**
     * Format a minor/major currency amount for display using tenant locale.
     */
    function money(int|float|string $amount, ?string $currency = null, bool $minorUnits = false): string
    {
        $currency ??= config('app.currency', 'USD');
        $value = $minorUnits ? ((float) $amount) / 100 : (float) $amount;
        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($value, $currency) ?: sprintf('%s %.2f', $currency, $value);
    }
}
