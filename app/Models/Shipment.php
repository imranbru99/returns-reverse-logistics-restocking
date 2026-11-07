<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ShipmentDirection;
use App\Enums\ShipmentStatus;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'rma_id',
        'direction',
        'carrier',
        'tracking_number',
        'status',
        'label_path',
        'rate_cents',
        'meta',
        'shipped_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'direction' => ShipmentDirection::class,
            'status' => ShipmentStatus::class,
            'meta' => 'array',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class);
    }

    public function receivingRecords(): HasMany
    {
        return $this->hasMany(ReceivingRecord::class);
    }
}
