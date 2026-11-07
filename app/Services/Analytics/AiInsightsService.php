<?php

declare(strict_types=1);

namespace App\Services\Analytics;

use App\Models\Tenant;
use ImranDevBd\AiHub\Facades\AIHub;

final class AiInsightsService
{
    public function __construct(
        private readonly ReturnsAnalyticsService $analytics,
    ) {}

    public static function forTenant(Tenant $tenant): self
    {
        return new self(ReturnsAnalyticsService::for($tenant));
    }

    /**
     * Natural-language Q&A over current tenant aggregates.
     *
     * @return array{answer: string, sources: array<string, mixed>}
     */
    public function askData(string $question): array
    {
        $snapshot = [
            'return_reasons' => $this->analytics->returnRateByReason()->take(10)->all(),
            'top_skus' => $this->analytics->returnRateBySku()->take(10)->all(),
            'inspection_outcomes' => $this->analytics->inspectionOutcomes(),
            'recovery_rate' => $this->analytics->recoveryRate(),
            'dwell_time' => $this->analytics->dwellTimeAggregates(),
        ];

        $prompt = <<<PROMPT
You are an analytics assistant for a returns operations platform. Answer the user's question using only the JSON snapshot below.
Respond in concise markdown.

Question: {$question}

Data snapshot:
{$this->encodeSnapshot($snapshot)}
PROMPT;

        $response = AIHub::prompt($prompt)->generate();

        return [
            'answer' => trim($response->content ?? (string) $response),
            'sources' => $snapshot,
        ];
    }

    /**
     * @return list<array{period: string, predicted_rmas: int, confidence: float}>
     */
    public function forecastReturns(int $periods = 3): array
    {
        if (config('returns.ai_insights.fake_forecast')) {
            return $this->fakeForecast($periods);
        }

        $byReason = $this->analytics->returnRateByReason();
        $baseline = (int) $byReason->sum('count');
        $perPeriod = max(1, (int) round($baseline / 4));

        $forecast = [];
        for ($i = 1; $i <= $periods; $i++) {
            $forecast[] = [
                'period' => now()->addWeeks($i)->format('Y-m-d'),
                'predicted_rmas' => $perPeriod + $i,
                'confidence' => max(0.5, 0.9 - ($i * 0.1)),
            ];
        }

        return $forecast;
    }

    /**
     * @return list<array{period: string, predicted_rmas: int, confidence: float}>
     */
    private function fakeForecast(int $periods): array
    {
        $forecast = [];
        for ($i = 1; $i <= $periods; $i++) {
            $forecast[] = [
                'period' => now()->addWeeks($i)->format('Y-m-d'),
                'predicted_rmas' => 100 + ($i * 12),
                'confidence' => 0.82,
            ];
        }

        return $forecast;
    }

    /**
     * @param  array<string, mixed>  $snapshot
     */
    private function encodeSnapshot(array $snapshot): string
    {
        return json_encode($snapshot, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}
