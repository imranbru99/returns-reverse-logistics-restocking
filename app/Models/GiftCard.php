<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftCard extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'code',
        'initial_balance_cents',
        'balance_cents',
        'currency',
        'status',
        'expires_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'initial_balance_cents' => 'integer',
            'balance_cents' => 'integer',
            'status' => \App\Enums\GiftCardStatus::class,
            'expires_at' => 'datetime',
            'meta' => 'array',
        ];
    }

}
