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

class ReturnCostLedger extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'rma_id',
        'shipping_cents',
        'labor_cents',
        'parts_cents',
        'fees_cents',
        'recovery_cents',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'shipping_cents' => 'integer',
            'labor_cents' => 'integer',
            'parts_cents' => 'integer',
            'fees_cents' => 'integer',
            'recovery_cents' => 'integer',
            'meta' => 'array',
        ];
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class, 'rma_id');
    }
}
