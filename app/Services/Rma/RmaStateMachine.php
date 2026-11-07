<?php

declare(strict_types=1);

namespace App\Services\Rma;

use App\Enums\RmaStatus;
use App\Models\Rma;
use App\Models\RmaStatusHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class RmaStateMachine
{
    public function transition(Rma $rma, RmaStatus $to, ?User $actor = null, ?string $notes = null): Rma
    {
        $from = $rma->status ?? RmaStatus::Requested;

        if (! $from->canTransitionTo($to)) {
            throw new InvalidArgumentException(
                "Cannot transition RMA from [{$from->value}] to [{$to->value}]."
            );
        }

        return DB::transaction(function () use ($rma, $from, $to, $actor, $notes): Rma {
            RmaStatusHistory::query()->create([
                'tenant_id' => $rma->tenant_id,
                'rma_id' => $rma->getKey(),
                'user_id' => $actor?->getKey(),
                'from_status' => $from,
                'to_status' => $to,
                'notes' => $notes,
            ]);

            $rma->forceFill(['status' => $to])->save();

            return $rma->refresh();
        });
    }
}
