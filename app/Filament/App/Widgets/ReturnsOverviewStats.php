<?php

declare(strict_types=1);

namespace App\Filament\App\Widgets;

use App\Facades\Module;
use App\Models\Rma;
use App\Services\Analytics\ReturnsAnalyticsService;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class ReturnsOverviewStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Returns overview';

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
        $rmaCount = Rma::query()->where('tenant_id', $tenant->getKey())->count();
        $recovery = $analytics->recoveryRate();
        $dwell = $analytics->dwellTimeAggregates();

        return [
            Stat::make('Open RMAs', (string) $rmaCount)
                ->description('All-time records')
                ->descriptionIcon('heroicon-m-arrow-uturn-left'),
            Stat::make('Recovery rate', number_format($recovery * 100, 1).'%')
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-m-chart-bar'),
            Stat::make('Avg dwell', $dwell['average_hours'] !== null ? $dwell['average_hours'].'h' : '—')
                ->description('RMA to inspection complete')
                ->descriptionIcon('heroicon-m-clock'),
        ];
    }
}
