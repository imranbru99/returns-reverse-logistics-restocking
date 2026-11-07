<?php

declare(strict_types=1);

namespace App\Services\Modules;

use App\Models\ModuleDefinition;
use App\Models\Tenant;
use App\Models\TenantModule;
use App\Services\Modules\Exceptions\ModuleHasDependentsException;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

final class ModuleManager
{
    private const CACHE_TTL_SECONDS = 300;

    public function __construct(
        private readonly ?Tenant $tenant = null,
    ) {}

    public function for(Tenant $tenant): self
    {
        return new self($tenant);
    }

    public function enabled(string $key, ?Tenant $tenant = null): bool
    {
        $tenant ??= $this->resolveTenant();

        if ($tenant === null) {
            $definition = $this->findDefinition($key);

            return $definition !== null && $definition->is_core && $definition->default_enabled;
        }

        $state = $this->tenantState($tenant);

        return (bool) ($state['enabled'][$key] ?? false);
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    public function enable(string $key, array $settings = []): void
    {
        $tenant = $this->requireTenant();

        foreach ($this->dependencies($key) as $dependency) {
            if (! $this->enabled($dependency, $tenant)) {
                $this->enable($dependency);
            }
        }

        $now = now();

        $existing = TenantModule::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $tenant->getKey())
            ->where('module', $key)
            ->first();

        $mergedSettings = $settings !== []
            ? array_merge($existing?->settings ?? [], $settings)
            : ($existing?->settings ?? []);

        TenantModule::query()->withoutGlobalScopes()->updateOrCreate(
            [
                'tenant_id' => $tenant->getKey(),
                'module' => $key,
            ],
            [
                'enabled' => true,
                'settings' => $mergedSettings,
                'enabled_at' => $now,
                'disabled_at' => null,
            ],
        );

        $this->flushTenantCache($tenant);
    }

    public function disable(string $key, bool $force = false): void
    {
        $tenant = $this->requireTenant();

        $blocking = array_values(array_filter(
            $this->dependents($key),
            fn (string $dependent): bool => $this->enabled($dependent, $tenant),
        ));

        if ($blocking !== [] && ! $force) {
            throw new ModuleHasDependentsException($key, $blocking);
        }

        $definition = $this->findDefinition($key);

        if ($definition?->is_core) {
            throw new InvalidArgumentException("Core module [{$key}] cannot be disabled.");
        }

        TenantModule::query()->withoutGlobalScopes()->updateOrCreate(
            [
                'tenant_id' => $tenant->getKey(),
                'module' => $key,
            ],
            [
                'enabled' => false,
                'disabled_at' => now(),
            ],
        );

        $this->flushTenantCache($tenant);
    }

    /**
     * @return array<int, string>
     */
    public function dependents(string $key): array
    {
        return ModuleDefinition::query()
            ->get(['key', 'dependencies'])
            ->filter(function (ModuleDefinition $module) use ($key): bool {
                $dependencies = $module->dependencies ?? [];

                return in_array($key, $dependencies, true);
            })
            ->pluck('key')
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public function dependencies(string $key): array
    {
        $definition = $this->findDefinition($key);

        if ($definition === null) {
            return $this->dependenciesFromConfig($key);
        }

        return array_values($definition->dependencies ?? []);
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    public function toggle(string $key, bool $enabled, array $settings = []): void
    {
        if ($enabled) {
            $this->enable($key, $settings);

            return;
        }

        $this->disable($key);
    }

    /**
     * @return Collection<int, ModuleDefinition>
     */
    public function all(): Collection
    {
        return ModuleDefinition::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * @return array<int, string>
     */
    public function enabledKeys(?Tenant $tenant = null): array
    {
        $tenant ??= $this->resolveTenant();

        if ($tenant === null) {
            return ModuleDefinition::query()
                ->where('default_enabled', true)
                ->orderBy('sort_order')
                ->pluck('key')
                ->all();
        }

        $state = $this->tenantState($tenant);

        return array_keys(array_filter($state['enabled']));
    }

    public function syncFromRegistry(): void
    {
        $registry = config('modules.registry', []);
        $sort = 0;

        foreach ($registry as $entry) {
            $sort++;

            ModuleDefinition::query()->updateOrCreate(
                ['key' => $entry['key']],
                [
                    'name' => $entry['name'],
                    'description' => $entry['description'] ?? null,
                    'category' => $entry['category'],
                    'dependencies' => $entry['dependencies'] ?? [],
                    'icon' => $entry['icon'] ?? null,
                    'price_tier' => $entry['price_tier'] ?? 'starter',
                    'default_enabled' => (bool) ($entry['default_enabled'] ?? false),
                    'sort_order' => $entry['sort_order'] ?? $sort,
                    'settings_schema' => $entry['settings_schema'] ?? null,
                    'is_core' => (bool) ($entry['is_core'] ?? false),
                ],
            );
        }
    }

    private function resolveTenant(): ?Tenant
    {
        return $this->tenant ?? TenantContext::get();
    }

    private function requireTenant(): Tenant
    {
        $tenant = $this->resolveTenant();

        if ($tenant === null) {
            throw new InvalidArgumentException('A tenant context is required for this operation.');
        }

        return $tenant;
    }

    private function findDefinition(string $key): ?ModuleDefinition
    {
        return ModuleDefinition::query()->find($key);
    }

    /**
     * @return array<int, string>
     */
    private function dependenciesFromConfig(string $key): array
    {
        $registry = config('modules.registry', []);

        foreach ($registry as $entry) {
            if (($entry['key'] ?? null) === $key) {
                return array_values($entry['dependencies'] ?? []);
            }
        }

        return [];
    }

    /**
     * @return array{enabled: array<string, bool>, settings: array<string, array<string, mixed>>}
     */
    private function tenantState(Tenant $tenant): array
    {
        return Cache::remember(
            $this->cacheKey($tenant),
            self::CACHE_TTL_SECONDS,
            function () use ($tenant): array {
                $defaults = ModuleDefinition::query()
                    ->where('default_enabled', true)
                    ->pluck('key')
                    ->mapWithKeys(fn (string $key): array => [$key => true])
                    ->all();

                $rows = TenantModule::query()
                    ->withoutGlobalScopes()
                    ->where('tenant_id', $tenant->getKey())
                    ->get(['module', 'enabled', 'settings']);

                $enabled = $defaults;
                $settings = [];

                foreach ($rows as $row) {
                    $enabled[$row->module] = (bool) $row->enabled;
                    $settings[$row->module] = $row->settings ?? [];
                }

                return [
                    'enabled' => $enabled,
                    'settings' => $settings,
                ];
            },
        );
    }

    private function flushTenantCache(Tenant $tenant): void
    {
        Cache::forget($this->cacheKey($tenant));
    }

    private function cacheKey(Tenant $tenant): string
    {
        return 'tenant:'.$tenant->getKey().':modules';
    }
}
