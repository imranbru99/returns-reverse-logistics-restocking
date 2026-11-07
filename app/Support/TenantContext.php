<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Tenant;

final class TenantContext
{
    private static ?Tenant $tenant = null;

    public static function set(?Tenant $tenant): void
    {
        self::$tenant = $tenant;
    }

    public static function get(): ?Tenant
    {
        return self::$tenant;
    }

    public static function id(): ?string
    {
        return self::$tenant?->getKey();
    }

    public static function clear(): void
    {
        self::$tenant = null;
    }

    public static function check(): bool
    {
        return self::$tenant !== null;
    }

    /**
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    public static function run(Tenant $tenant, callable $callback): mixed
    {
        $previous = self::$tenant;
        self::set($tenant);

        try {
            return $callback();
        } finally {
            self::$tenant = $previous;
        }
    }
}
