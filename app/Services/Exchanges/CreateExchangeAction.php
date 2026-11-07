<?php

declare(strict_types=1);

namespace App\Services\Exchanges;

use App\Enums\ExchangeOrderStatus;
use App\Models\ExchangeOrder;
use App\Models\Rma;
use App\Support\NumberGenerator;
use Illuminate\Support\Facades\DB;

final class CreateExchangeAction
{
    public function __construct(
        private readonly NumberGenerator $numbers,
    ) {}

    /**
     * @param  array<int, array<string, mixed>>  $newOrderItems
     */
    public function execute(Rma $rma, array $newOrderItems): ExchangeOrder
    {
        if ($newOrderItems === []) {
            throw new \InvalidArgumentException('Exchange requires at least one replacement line.');
        }

        $tenantId = (string) $rma->tenant_id;

        return DB::transaction(function () use ($rma, $newOrderItems, $tenantId): ExchangeOrder {
            $number = $this->numbers->next($tenantId, 'exchange', 'EXC');

            return ExchangeOrder::query()->create([
                'tenant_id' => $tenantId,
                'rma_id' => $rma->getKey(),
                'number' => $number,
                'status' => ExchangeOrderStatus::Pending,
                'new_order_items' => $newOrderItems,
                'meta' => ['created_from_rma' => $rma->number],
            ]);
        });
    }
}
