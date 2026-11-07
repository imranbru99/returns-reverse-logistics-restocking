<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Providers\Filament\Concerns\ConfiguresFilamentPanels;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

class SuperAdminPanelProvider extends PanelProvider
{
    use ConfiguresFilamentPanels;

    public function panel(Panel $panel): Panel
    {
        return $this->configurePanelDefaults(
            $panel
                ->id('super-admin')
                ->path('super-admin')
                ->brandName('Returns Platform')
                ->colors([
                    'primary' => Color::Teal,
                ])
                ->discoverResources(in: app_path('Filament/SuperAdmin/Resources'), for: 'App\Filament\SuperAdmin\Resources')
                ->discoverPages(in: app_path('Filament/SuperAdmin/Pages'), for: 'App\Filament\SuperAdmin\Pages')
                ->discoverWidgets(in: app_path('Filament/SuperAdmin/Widgets'), for: 'App\Filament\SuperAdmin\Widgets'),
        );
    }
}
