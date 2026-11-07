<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageMeter extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;

    public const METRIC_RMAS = 'rmas';

    protected $fillable = [
        'tenant_id',
        'subscription_id',
        'metric',
        'quantity',
        'period_start',
        'period_end',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'datetime',
            'period_end' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
