<?php

declare(strict_types=1);

namespace App\Providers;

use App\Facades\Module;
use App\Services\Modules\ModuleManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

final class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class, fn (): ModuleManager => new ModuleManager);

        $this->app->alias(ModuleManager::class, 'module.manager');
    }

    public function boot(): void
    {
        Blade::if('module', fn (string $key): bool => Module::enabled($key));
    }
}
