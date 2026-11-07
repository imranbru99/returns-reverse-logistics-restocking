<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Rma;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeOrder extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'rma_id',
        'number',
        'status',
        'new_order_items',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\ExchangeOrderStatus::class,
            'new_order_items' => 'array',
            'meta' => 'array',
        ];
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class, 'rma_id');
    }
}
