<?php

declare(strict_types=1);

namespace App\Filament\App\Widgets;

use App\Facades\Module;
use App\Services\Analytics\ReturnsAnalyticsService;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;

final class InspectionOutcomesChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Inspection outcomes (grade)';

    public static function canView(): bool
    {
        $tenant = Filament::getTenant();

        return $tenant !== null && Module::enabled('analytics-reporting', $tenant);
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $tenant = Filament::getTenant();

        if ($tenant === null) {
            return ['datasets' => [], 'labels' => []];
        }

        $byGrade = ReturnsAnalyticsService::for($tenant)->inspectionOutcomes()['by_grade'];
        $labels = array_keys($byGrade);
        $data = array_values($byGrade);

        return [
            'datasets' => [
                [
                    'label' => 'Inspections',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
