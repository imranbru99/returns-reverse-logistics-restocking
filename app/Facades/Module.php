<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\Modules\ModuleManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool enabled(string $key, ?\App\Models\Tenant $tenant = null)
 * @method static self for(\App\Models\Tenant $tenant)
 * @method static void enable(string $key, array $settings = [])
 * @method static void disable(string $key, bool $force = false)
 * @method static array dependents(string $key)
 * @method static array dependencies(string $key)
 * @method static void toggle(string $key, bool $enabled, array $settings = [])
 * @method static \Illuminate\Database\Eloquent\Collection<int, \App\Models\ModuleDefinition> all()
 * @method static array enabledKeys(?\App\Models\Tenant $tenant = null)
 * @method static void syncFromRegistry()
 *
 * @see ModuleManager
 */
final class Module extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ModuleManager::class;
    }
}
