<?php

declare(strict_types=1);

namespace App\Services\Rma;

use App\Enums\RmaStatus;
use App\Models\Order;
use App\Models\Rma;
use App\Models\RmaItem;
use App\Models\RmaStatusHistory;
use App\Support\NumberGenerator;
use Illuminate\Support\Facades\DB;

final class CreateRmaAction
{
    public function __construct(
        private readonly NumberGenerator $numbers,
    ) {}

    /**
     * @param  array<int, array{order_item_id: string, quantity: int, reason?: string|null}>  $lines
     */
    public function execute(
        Order $order,
        array $lines,
        ?string $reason = null,
        ?string $customerNotes = null,
    ): Rma {
        $tenantId = (string) $order->tenant_id;

        return DB::transaction(function () use ($order, $lines, $reason, $customerNotes, $tenantId): Rma {
            $number = $this->numbers->next($tenantId, 'rma', 'RMA');

            $rma = Rma::query()->create([
                'tenant_id' => $tenantId,
                'customer_id' => $order->customer_id,
                'order_id' => $order->getKey(),
                'number' => $number,
                'status' => RmaStatus::Requested,
                'reason' => $reason,
                'customer_notes' => $customerNotes,
            ]);

            foreach ($lines as $line) {
                $orderItem = $order->items()->whereKey($line['order_item_id'])->first();

                if ($orderItem === null) {
                    continue;
                }

                RmaItem::query()->create([
                    'tenant_id' => $tenantId,
                    'rma_id' => $rma->getKey(),
                    'order_item_id' => $orderItem->getKey(),
                    'product_id' => $orderItem->product_id,
                    'quantity' => $line['quantity'] ?? 1,
                    'reason' => $line['reason'] ?? null,
                ]);
            }

            RmaStatusHistory::query()->create([
                'tenant_id' => $tenantId,
                'rma_id' => $rma->getKey(),
                'from_status' => null,
                'to_status' => RmaStatus::Requested,
                'notes' => 'RMA created',
            ]);

            return $rma->load('items');
        });
    }
}
