<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Http\Middleware\SetCurrentTenant;
use App\Models\Tenant;
use App\Providers\Filament\Concerns\ConfiguresFilamentPanels;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class MerchantPanelProvider extends PanelProvider
{
    use ConfiguresFilamentPanels;

    public function panel(Panel $panel): Panel
    {
        return $this->configurePanelDefaults(
            $panel
                ->id('merchant')
                ->path('merchant')
                ->brandName('Merchant Portal')
                ->colors([
                    'primary' => Color::Teal,
                ])
                ->tenant(Tenant::class, slugAttribute: 'slug')
                ->tenantMiddleware([
                    SetCurrentTenant::class,
                ], isPersistent: true)
                ->discoverResources(in: app_path('Filament/Merchant/Resources'), for: 'App\Filament\Merchant\Resources')
                ->discoverPages(in: app_path('Filament/Merchant/Pages'), for: 'App\Filament\Merchant\Pages')
                ->discoverWidgets(in: app_path('Filament/Merchant/Widgets'), for: 'App\Filament\Merchant\Widgets'),
        );
    }
}
