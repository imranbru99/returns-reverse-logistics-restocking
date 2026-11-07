<?php

declare(strict_types=1);

namespace App\Services\Restocking;

use App\Enums\PutawayTaskStatus;
use App\Models\InventoryItem;
use App\Models\PutawayTask;
use App\Models\User;
use App\Models\WarehouseBin;
use Illuminate\Support\Facades\DB;

final class CreatePutawayTaskAction
{
    public function execute(InventoryItem $item, ?User $assignee = null, ?WarehouseBin $preferredBin = null): PutawayTask
    {
        return DB::transaction(function () use ($item, $assignee, $preferredBin): PutawayTask {
            $toBin = $preferredBin ?? $this->suggestBin($item);

            return PutawayTask::query()->create([
                'tenant_id' => $item->tenant_id,
                'inventory_item_id' => $item->getKey(),
                'from_bin_id' => $item->warehouse_bin_id,
                'to_bin_id' => $toBin->getKey(),
                'status' => PutawayTaskStatus::Pending,
                'assigned_to' => $assignee?->getKey(),
            ]);
        });
    }

    private function suggestBin(InventoryItem $item): WarehouseBin
    {
        $bins = WarehouseBin::query()
            ->where('tenant_id', $item->tenant_id)
            ->withSum('inventoryItems as used_capacity', 'quantity_on_hand')
            ->orderBy('code')
            ->get();

        $selected = $bins->first(function (WarehouseBin $bin): bool {
            $capacity = (int) ($bin->capacity ?? 0);
            $used = (int) ($bin->used_capacity ?? 0);

            if ($capacity <= 0) {
                return true;
            }

            return $used < $capacity;
        });

        if ($selected === null) {
            throw new \RuntimeException('No warehouse bin with available capacity was found.');
        }

        return $selected;
    }
}
