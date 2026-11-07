<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RmaStatus;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Rma extends Model implements HasMedia
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'order_id',
        'return_policy_id',
        'number',
        'status',
        'reason',
        'customer_notes',
        'internal_notes',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'status' => RmaStatus::class,
            'meta' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function returnPolicy(): BelongsTo
    {
        return $this->belongsTo(ReturnPolicy::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RmaItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(RmaStatusHistory::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function receivingRecords(): HasMany
    {
        return $this->hasMany(ReceivingRecord::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('customer_photos');
    }
}
