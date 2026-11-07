<?php

declare(strict_types=1);

namespace App\Services\Disposition;

use App\Enums\DispositionType;
use App\Enums\RmaStatus;
use App\Models\Disposition;
use App\Models\Inspection;
use App\Models\InventoryItem;
use App\Services\Rma\RmaStateMachine;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class ApplyDispositionAction
{
    public function __construct(
        private readonly RmaStateMachine $rmaStateMachine,
    ) {}

    public function execute(
        Inspection $inspection,
        DispositionType $type,
        int $quantity = 1,
        ?string $notes = null,
    ): Disposition {
        return DB::transaction(function () use ($inspection, $type, $quantity, $notes): Disposition {
            $disposition = Disposition::query()->create([
                'tenant_id' => $inspection->tenant_id,
                'inspection_id' => $inspection->getKey(),
                'rma_item_id' => $inspection->rma_item_id,
                'product_id' => $inspection->product_id,
                'type' => $type,
                'quantity' => $quantity,
                'notes' => $notes,
                'meta' => ['applied_at' => now()->toIso8601String()],
            ]);

            if ($this->affectsInventory($type) && $inspection->product_id !== null) {
                $this->adjustInventory($inspection, $type, $quantity);
            }

            $rma = $inspection->rma;
            if ($rma !== null && $rma->status === RmaStatus::Inspected) {
                $this->rmaStateMachine->transition($rma, RmaStatus::Dispositioned);
            }

            return $disposition;
        });
    }

    private function affectsInventory(DispositionType $type): bool
    {
        return in_array($type, [
            DispositionType::RestockAsNew,
            DispositionType::RestockOpenBox,
            DispositionType::Resell,
        ], true);
    }

    private function adjustInventory(Inspection $inspection, DispositionType $type, int $quantity): void
    {
        $condition = match ($type) {
            DispositionType::RestockAsNew => 'new',
            DispositionType::RestockOpenBox => 'open_box',
            DispositionType::Resell => 'used',
            default => 'unknown',
        };

        $item = InventoryItem::query()
            ->where('product_id', $inspection->product_id)
            ->first();

        if ($item === null) {
            throw new InvalidArgumentException('No inventory item found for restock disposition.');
        }

        $item->increment('quantity_on_hand', $quantity);
        $item->forceFill(['condition' => $condition])->save();
    }
}
