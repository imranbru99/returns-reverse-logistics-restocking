<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\NumberSequence;
use Illuminate\Support\Facades\Cache;

final class NumberGenerator
{
    public function next(string $tenantId, string $sequence, ?string $prefix = null): string
    {
        $lockKey = "number-sequence:{$tenantId}:{$sequence}";

        return Cache::lock($lockKey, 10)->block(5, function () use ($tenantId, $sequence, $prefix): string {
            $record = NumberSequence::query()
                ->withoutGlobalScopes()
                ->firstOrCreate(
                    [
                        'tenant_id' => $tenantId,
                        'sequence' => $sequence,
                    ],
                    [
                        'prefix' => $prefix ?? strtoupper($sequence),
                        'last_number' => 0,
                        'padding' => 6,
                    ],
                );

            $record->increment('last_number');
            $record->refresh();

            $label = $record->prefix ?? strtoupper($sequence);
            $padding = max(1, (int) $record->padding);

            return sprintf('%s-%0'.$padding.'d', $label, $record->last_number);
        });
    }
}
