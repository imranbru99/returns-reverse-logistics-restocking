<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkTask extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'queue',
        'priority',
        'sla_due_at',
        'status',
        'assignee_id',
        'warehouse_id',
        'title',
        'subject_type',
        'subject_id',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'queue' => \App\Enums\WorkTaskQueue::class,
            'priority' => 'integer',
            'sla_due_at' => 'datetime',
            'status' => \App\Enums\WorkTaskStatus::class,
            'meta' => 'array',
        ];
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
