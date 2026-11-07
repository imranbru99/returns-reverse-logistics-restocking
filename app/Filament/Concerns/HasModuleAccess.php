<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use App\Facades\Module;

trait HasModuleAccess
{
    public static function shouldRegisterNavigation(): bool
    {
        if (! static::moduleAccessEnabled()) {
            return false;
        }

        return parent::shouldRegisterNavigation();
    }

    public static function canAccess(): bool
    {
        if (! static::moduleAccessEnabled()) {
            return false;
        }

        return parent::canAccess();
    }

    protected static function moduleAccessEnabled(): bool
    {
        /** @var class-string<static> $class */
        $class = static::class;

        if (! property_exists($class, 'module')) {
            return true;
        }

        $module = $class::$module ?? null;

        if ($module === null) {
            return true;
        }

        return Module::enabled($module);
    }
}
