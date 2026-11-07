<?php

declare(strict_types=1);

namespace App\Filament\App\Widgets;

use App\Facades\Module;
use App\Services\Analytics\ReturnsAnalyticsService;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class RecoveryRateWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Recovery performance';

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        $tenant = Filament::getTenant();

        return $tenant !== null && Module::enabled('analytics-reporting', $tenant);
    }

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        if ($tenant === null) {
            return [];
        }

        $analytics = ReturnsAnalyticsService::for($tenant);
        $rate = $analytics->recoveryRate();
        $topReason = $analytics->returnRateByReason()->first();

        return [
            Stat::make('Recovery rate', number_format($rate * 100, 1).'%')
                ->description('Restock / resell / refurb share')
                ->color('success'),
            Stat::make('Top reason', $topReason['reason'] ?? '—')
                ->description((string) ($topReason['count'] ?? 0).' RMAs')
                ->color('info'),
        ];
    }
}
