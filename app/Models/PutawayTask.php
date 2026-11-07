<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PutawayTaskStatus;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PutawayTask extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'inventory_item_id',
        'from_bin_id',
        'to_bin_id',
        'status',
        'assigned_to',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => PutawayTaskStatus::class,
            'completed_at' => 'datetime',
        ];
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function fromBin(): BelongsTo
    {
        return $this->belongsTo(WarehouseBin::class, 'from_bin_id');
    }

    public function toBin(): BelongsTo
    {
        return $this->belongsTo(WarehouseBin::class, 'to_bin_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
