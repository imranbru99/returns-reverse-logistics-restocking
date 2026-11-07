<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DispositionType;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Disposition extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'inspection_id',
        'rma_item_id',
        'product_id',
        'type',
        'quantity',
        'notes',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'type' => DispositionType::class,
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

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function rmaItem(): BelongsTo
    {
        return $this->belongsTo(RmaItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
