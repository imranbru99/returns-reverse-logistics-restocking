<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Facades\Module;
use App\Services\Analytics\ReturnsAnalyticsService;
use Filament\Facades\Filament;
use Filament\Pages\Page;

final class ExecutiveDashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static string|\UnitEnum|null $navigationGroup = 'Intelligence';

    protected static ?string $navigationLabel = 'Executive dashboard';

    protected static ?string $title = 'Executive dashboard';

    protected static ?string $slug = 'executive-dashboard';

    protected string $view = 'filament.app.pages.executive-dashboard';

    /** @var array<string, mixed> */
    public array $summary = [];

    public static function canAccess(): bool
    {
        $tenant = Filament::getTenant();

        return $tenant !== null && Module::enabled('executive-dashboards', $tenant);
    }

    public function mount(): void
    {
        $tenant = Filament::getTenant();

        if ($tenant === null) {
            return;
        }

        $analytics = ReturnsAnalyticsService::for($tenant);

        $this->summary = [
            'recovery_rate' => $analytics->recoveryRate(),
            'dwell' => $analytics->dwellTimeAggregates(),
            'inspections' => $analytics->inspectionOutcomes(),
            'top_skus' => $analytics->returnRateBySku()->take(5)->all(),
            'reasons' => $analytics->returnRateByReason()->take(5)->all(),
        ];
    }
}
