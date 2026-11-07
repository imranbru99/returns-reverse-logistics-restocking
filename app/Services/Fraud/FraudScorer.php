<?php

declare(strict_types=1);

namespace App\Services\Fraud;

use App\Enums\FraudSignalStatus;
use App\Models\Customer;
use App\Models\FraudSignal;
use App\Models\Order;
use App\Models\Rma;
use Illuminate\Support\Facades\DB;
use ImranDevBd\AiHub\Facades\AIHub;

final class FraudScorer
{
    /**
     * @return array{score: int, signals: array<int, string>, fraud_signal: FraudSignal}
     */
    public function score(?Customer $customer = null, ?Order $order = null, ?Rma $rma = null): array
    {
        $signals = [];
        $score = 0;

        if ($customer !== null) {
            $recentRmas = Rma::query()
                ->where('customer_id', $customer->getKey())
                ->where('created_at', '>=', now()->subDays(30))
                ->count();
            if ($recentRmas >= 5) {
                $signals[] = 'high_return_velocity';
                $score += 25;
            }
        }

        if ($order !== null && (float) $order->total >= 500) {
            $signals[] = 'high_value_order';
            $score += 10;
        }

        if ($rma !== null && $rma->items()->sum('quantity') >= 3) {
            $signals[] = 'multi_item_return';
            $score += 15;
        }

        if ($this->aiHubAvailable() && ($customer !== null || $order !== null || $rma !== null)) {
            $aiBoost = $this->aiAdjustment($customer, $order, $rma);
            if ($aiBoost > 0) {
                $signals[] = 'ai_risk_hint';
                $score += $aiBoost;
            }
        }

        $score = min(100, $score);
        $tenantId = $customer?->tenant_id ?? $order?->tenant_id ?? $rma?->tenant_id;

        $fraudSignal = DB::transaction(function () use ($tenantId, $customer, $order, $rma, $score, $signals): FraudSignal {
            return FraudSignal::query()->create([
                'tenant_id' => $tenantId,
                'customer_id' => $customer?->getKey(),
                'order_id' => $order?->getKey(),
                'rma_id' => $rma?->getKey(),
                'score' => $score,
                'signals' => $signals,
                'status' => $score >= 50 ? FraudSignalStatus::Reviewing : FraudSignalStatus::Open,
            ]);
        });

        return [
            'score' => $score,
            'signals' => $signals,
            'fraud_signal' => $fraudSignal,
        ];
    }

    private function aiHubAvailable(): bool
    {
        return class_exists(AIHub::class);
    }

    private function aiAdjustment(?Customer $customer, ?Order $order, ?Rma $rma): int
    {
        try {
            $summary = json_encode([
                'customer_id' => $customer?->getKey(),
                'order_total' => $order?->total,
                'rma_reason' => $rma?->reason,
            ]);
            $response = AIHub::prompt("Return fraud risk 0-20 integer only for context: {$summary}")->generate();
            $value = (int) preg_replace('/\D/', '', (string) $response);

            return min(20, max(0, $value));
        } catch (\Throwable) {
            return 0;
        }
    }
}
