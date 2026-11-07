<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Facades\Module;
use App\Models\ModuleDefinition;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ModuleMarketplace extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $title = 'Module marketplace';

    protected static ?string $slug = 'module-marketplace';

    protected string $view = 'filament.app.pages.module-marketplace';

    /** @var array<int, array{key: string, name: string, description: string, enabled: bool, is_core: bool}> */
    public array $moduleRows = [];

    public function mount(): void
    {
        $this->refreshModules();
    }

    public function toggleModule(string $key): void
    {
        $tenant = Filament::getTenant();

        if ($tenant === null) {
            return;
        }

        $manager = Module::for($tenant);

        try {
            if (Module::enabled($key, $tenant)) {
                $manager->disable($key);
            } else {
                $manager->enable($key);
            }
        } catch (\Throwable $exception) {
            Notification::make()
                ->title('Unable to update module')
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return;
        }

        $this->refreshModules();

        Notification::make()
            ->title('Module updated')
            ->success()
            ->send();
    }

    protected function refreshModules(): void
    {
        $tenant = Filament::getTenant();

        $this->moduleRows = ModuleDefinition::query()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (ModuleDefinition $definition): array => [
                'key' => $definition->key,
                'name' => $definition->name,
                'description' => $definition->description,
                'enabled' => $tenant ? Module::enabled($definition->key, $tenant) : $definition->default_enabled,
                'is_core' => (bool) $definition->is_core,
            ])
            ->all();
    }
}
