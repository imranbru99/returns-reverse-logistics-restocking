<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Http\Middleware\SetCurrentTenant;
use App\Models\Tenant;
use App\Providers\Filament\Concerns\ConfiguresFilamentPanels;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class VendorPanelProvider extends PanelProvider
{
    use ConfiguresFilamentPanels;

    public function panel(Panel $panel): Panel
    {
        return $this->configurePanelDefaults(
            $panel
                ->id('vendor')
                ->path('vendor')
                ->brandName('Vendor Portal')
                ->colors([
                    'primary' => Color::Teal,
                ])
                ->tenant(Tenant::class, slugAttribute: 'slug')
                ->tenantMiddleware([
                    SetCurrentTenant::class,
                ], isPersistent: true)
                ->discoverResources(in: app_path('Filament/Vendor/Resources'), for: 'App\Filament\Vendor\Resources')
                ->discoverPages(in: app_path('Filament/Vendor/Pages'), for: 'App\Filament\Vendor\Pages')
                ->discoverWidgets(in: app_path('Filament/Vendor/Widgets'), for: 'App\Filament\Vendor\Widgets'),
        );
    }
}
