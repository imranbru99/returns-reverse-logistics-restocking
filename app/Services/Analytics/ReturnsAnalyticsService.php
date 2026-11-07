<?php

declare(strict_types=1);

namespace App\Services\Analytics;

use App\Enums\DispositionType;
use App\Models\Disposition;
use App\Models\Inspection;
use App\Models\OrderItem;
use App\Models\Rma;
use App\Models\RmaItem;
use App\Models\Tenant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class ReturnsAnalyticsService
{
    public function __construct(
        private readonly Tenant $tenant,
    ) {}

    public static function for(Tenant $tenant): self
    {
        return new self($tenant);
    }

    /**
     * @return Collection<int, array{product_id: string, sku: string|null, name: string|null, return_count: int, return_rate: float}>
     */
    public function returnRateBySku(?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $from ??= now()->subDays(30);
        $to ??= now();

        $returns = RmaItem::query()
            ->where('tenant_id', $this->tenant->getKey())
            ->whereHas('rma', fn ($q) => $q->whereBetween('created_at', [$from, $to]))
            ->select('product_id', DB::raw('SUM(quantity) as return_count'))
            ->groupBy('product_id')
            ->with('product:id,sku,name')
            ->get();

        $sold = OrderItem::query()
            ->where('tenant_id', $this->tenant->getKey())
            ->whereHas('order', fn ($q) => $q->whereBetween('created_at', [$from, $to]))
            ->select('product_id', DB::raw('SUM(quantity) as sold_count'))
            ->groupBy('product_id')
            ->pluck('sold_count', 'product_id');

        return $returns->map(function ($row) use ($sold) {
            $soldCount = (int) ($sold[$row->product_id] ?? 0);
            $returnCount = (int) $row->return_count;
            $rate = $soldCount > 0 ? round($returnCount / $soldCount, 4) : 0.0;

            return [
                'product_id' => (string) $row->product_id,
                'sku' => $row->product?->sku,
                'name' => $row->product?->name,
                'return_count' => $returnCount,
                'return_rate' => $rate,
            ];
        })->sortByDesc('return_count')->values();
    }

    /**
     * @return Collection<int, array{reason: string, count: int}>
     */
    public function returnRateByReason(?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $from ??= now()->subDays(30);
        $to ??= now();

        return Rma::query()
            ->where('tenant_id', $this->tenant->getKey())
            ->whereBetween('created_at', [$from, $to])
            ->select('reason', DB::raw('COUNT(*) as count'))
            ->groupBy('reason')
            ->orderByDesc('count')
            ->get()
            ->map(fn ($row) => [
                'reason' => (string) ($row->reason ?: 'unspecified'),
                'count' => (int) $row->count,
            ]);
    }

    /**
     * @return array{by_grade: array<string, int>, by_status: array<string, int>, total: int}
     */
    public function inspectionOutcomes(?Carbon $from = null, ?Carbon $to = null): array
    {
        $from ??= now()->subDays(30);
        $to ??= now();

        $inspections = Inspection::query()
            ->where('tenant_id', $this->tenant->getKey())
            ->whereBetween('created_at', [$from, $to])
            ->get(['grade', 'status']);

        $byGrade = [];
        $byStatus = [];

        foreach ($inspections as $inspection) {
            $gradeKey = $inspection->grade?->value ?? 'none';
            $statusKey = $inspection->status->value;
            $byGrade[$gradeKey] = ($byGrade[$gradeKey] ?? 0) + 1;
            $byStatus[$statusKey] = ($byStatus[$statusKey] ?? 0) + 1;
        }

        return [
            'by_grade' => $byGrade,
            'by_status' => $byStatus,
            'total' => $inspections->count(),
        ];
    }

    /**
     * Share of inspected units with a recoverable disposition (restock, resell, refurbish).
     */
    public function recoveryRate(?Carbon $from = null, ?Carbon $to = null): float
    {
        $from ??= now()->subDays(30);
        $to ??= now();

        $recoverable = [
            DispositionType::RestockAsNew,
            DispositionType::RestockOpenBox,
            DispositionType::Refurbish,
            DispositionType::Resell,
        ];

        $totalQty = (int) Disposition::query()
            ->where('tenant_id', $this->tenant->getKey())
            ->whereBetween('created_at', [$from, $to])
            ->sum('quantity');

        if ($totalQty === 0) {
            return 0.0;
        }

        $recoveredQty = (int) Disposition::query()
            ->where('tenant_id', $this->tenant->getKey())
            ->whereBetween('created_at', [$from, $to])
            ->whereIn('type', array_map(fn (DispositionType $t) => $t->value, $recoverable))
            ->sum('quantity');

        return round($recoveredQty / $totalQty, 4);
    }

    /**
     * @return array{average_hours: float|null, sample_size: int}
     */
    public function dwellTimeAggregates(?Carbon $from = null, ?Carbon $to = null): array
    {
        $from ??= now()->subDays(30);
        $to ??= now();

        $rows = Inspection::query()
            ->where('tenant_id', $this->tenant->getKey())
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$from, $to])
            ->with('rma:id,created_at')
            ->get(['id', 'rma_id', 'completed_at']);

        if ($rows->isEmpty()) {
            return ['average_hours' => null, 'sample_size' => 0];
        }

        $hours = $rows->map(function (Inspection $inspection) {
            $started = $inspection->rma?->created_at;

            if ($started === null || $inspection->completed_at === null) {
                return null;
            }

            return $started->diffInMinutes($inspection->completed_at) / 60;
        })->filter();

        $avg = $hours->isEmpty() ? null : round($hours->average(), 2);

        return ['average_hours' => $avg, 'sample_size' => $hours->count()];
    }
}
