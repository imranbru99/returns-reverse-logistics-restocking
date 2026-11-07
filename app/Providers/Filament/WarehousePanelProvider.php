<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Http\Middleware\SetCurrentTenant;
use App\Models\Tenant;
use App\Providers\Filament\Concerns\ConfiguresFilamentPanels;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;

class WarehousePanelProvider extends PanelProvider
{
    use ConfiguresFilamentPanels;

    public function panel(Panel $panel): Panel
    {
        return $this->configurePanelDefaults(
            $panel
                ->id('warehouse')
                ->path('warehouse')
                ->brandName('Warehouse')
                ->colors([
                    'primary' => Color::Emerald,
                ])
                ->spa()
                ->sidebarCollapsibleOnDesktop(false)
                ->maxContentWidth(Width::Full)
                ->tenant(Tenant::class, slugAttribute: 'slug')
                ->tenantMiddleware([
                    SetCurrentTenant::class,
                ], isPersistent: true)
                ->discoverResources(in: app_path('Filament/Warehouse/Resources'), for: 'App\Filament\Warehouse\Resources')
                ->discoverPages(in: app_path('Filament/Warehouse/Pages'), for: 'App\Filament\Warehouse\Pages')
                ->discoverWidgets(in: app_path('Filament/Warehouse/Widgets'), for: 'App\Filament\Warehouse\Widgets'),
        );
    }
}
