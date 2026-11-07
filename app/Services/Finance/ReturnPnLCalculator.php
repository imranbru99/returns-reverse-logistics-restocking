<?php

declare(strict_types=1);

namespace App\Services\Finance;

use App\Models\ReturnCostLedger;
use App\Models\Rma;

final class ReturnPnLCalculator
{
    /**
     * @return array{
     *     costs_cents: int,
     *     recovery_cents: int,
     *     net_cents: int,
     *     breakdown: array<string, int>
     * }
     */
    public function forRma(Rma $rma, ?ReturnCostLedger $ledger = null): array
    {
        $ledger ??= ReturnCostLedger::query()->where('rma_id', $rma->getKey())->first();

        if ($ledger === null) {
            return [
                'costs_cents' => 0,
                'recovery_cents' => 0,
                'net_cents' => 0,
                'breakdown' => [
                    'shipping_cents' => 0,
                    'labor_cents' => 0,
                    'parts_cents' => 0,
                    'fees_cents' => 0,
                    'recovery_cents' => 0,
                ],
            ];
        }

        $costs = $ledger->shipping_cents + $ledger->labor_cents + $ledger->parts_cents + $ledger->fees_cents;
        $recovery = $ledger->recovery_cents;

        return [
            'costs_cents' => $costs,
            'recovery_cents' => $recovery,
            'net_cents' => $recovery - $costs,
            'breakdown' => [
                'shipping_cents' => $ledger->shipping_cents,
                'labor_cents' => $ledger->labor_cents,
                'parts_cents' => $ledger->parts_cents,
                'fees_cents' => $ledger->fees_cents,
                'recovery_cents' => $ledger->recovery_cents,
            ],
        ];
    }
}
