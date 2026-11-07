<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RefurbishmentWorkOrderStatus;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefurbishmentWorkOrder extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'rma_item_id',
        'status',
        'parts_cost_cents',
        'labor_minutes',
        'notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => RefurbishmentWorkOrderStatus::class,
            'completed_at' => 'datetime',
        ];
    }

    public function rmaItem(): BelongsTo
    {
        return $this->belongsTo(RmaItem::class);
    }
}
