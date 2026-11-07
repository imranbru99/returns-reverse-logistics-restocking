<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RmaStatus;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RmaStatusHistory extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'rma_id',
        'user_id',
        'from_status',
        'to_status',
        'notes',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'to_status' => RmaStatus::class,
            'meta' => 'array',
        ];
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
