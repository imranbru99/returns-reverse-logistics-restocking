<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Grade;
use App\Enums\InspectionStatus;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Inspection extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'rma_id',
        'rma_item_id',
        'product_id',
        'inspector_id',
        'number',
        'status',
        'grade',
        'notes',
        'checklist',
        'started_at',
        'completed_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'status' => InspectionStatus::class,
            'grade' => Grade::class,
            'checklist' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
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

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class);
    }

    public function rmaItem(): BelongsTo
    {
        return $this->belongsTo(RmaItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function dispositions(): HasMany
    {
        return $this->hasMany(Disposition::class);
    }
}
