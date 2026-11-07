<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Http\Middleware\SetCurrentTenant;
use App\Models\Tenant;
use App\Providers\Filament\Concerns\ConfiguresFilamentPanels;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class AppPanelProvider extends PanelProvider
{
    use ConfiguresFilamentPanels;

    public function panel(Panel $panel): Panel
    {
        return $this->configurePanelDefaults(
            $panel
                ->default()
                ->id('app')
                ->path('app')
                ->brandName(fn (): string => Filament::getTenant()?->getFilamentName()
                    ?? (string) config('returns.brand.name', 'Returns Platform'))
                ->colors([
                    'primary' => Color::Teal,
                ])
                ->tenant(Tenant::class, slugAttribute: 'slug')
                ->tenantMiddleware([
                    SetCurrentTenant::class,
                ], isPersistent: true)
                ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\Filament\App\Resources')
                ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\Filament\App\Pages')
                ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\Filament\App\Widgets'),
        );
    }
}
