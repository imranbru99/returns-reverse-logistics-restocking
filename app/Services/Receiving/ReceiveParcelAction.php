<?php

declare(strict_types=1);

namespace App\Services\Receiving;

use App\Enums\RmaStatus;
use App\Models\ReceivingRecord;
use App\Models\Rma;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\Rma\RmaStateMachine;
use Illuminate\Support\Facades\DB;

final class ReceiveParcelAction
{
    public function __construct(
        private readonly RmaStateMachine $rmaStateMachine,
    ) {}

    /**
     * @param  array<int, string>  $photos
     */
    public function execute(
        Warehouse $warehouse,
        User $receiver,
        ?Rma $rma = null,
        ?Shipment $shipment = null,
        int $expectedQty = 1,
        int $receivedQty = 1,
        bool $damagedInTransit = false,
        bool $orphan = false,
        array $photos = [],
        ?string $notes = null,
    ): ReceivingRecord {
        return DB::transaction(function () use (
            $warehouse,
            $receiver,
            $rma,
            $shipment,
            $expectedQty,
            $receivedQty,
            $damagedInTransit,
            $orphan,
            $photos,
            $notes,
        ): ReceivingRecord {
            if ($rma === null && $shipment !== null) {
                $rma = $shipment->rma;
            }

            $record = ReceivingRecord::query()->create([
                'tenant_id' => $warehouse->tenant_id,
                'rma_id' => $rma?->getKey(),
                'shipment_id' => $shipment?->getKey(),
                'warehouse_id' => $warehouse->getKey(),
                'received_by' => $receiver->getKey(),
                'expected_qty' => $expectedQty,
                'received_qty' => $receivedQty,
                'damaged_in_transit' => $damagedInTransit,
                'orphan' => $orphan,
                'photos' => $photos,
                'notes' => $notes,
                'received_at' => now(),
            ]);

            if ($rma !== null && in_array($rma->status, [RmaStatus::InTransit, RmaStatus::LabelIssued], true)) {
                $this->rmaStateMachine->transition($rma, RmaStatus::Received, $receiver, $notes);
            }

            return $record;
        });
    }
}
