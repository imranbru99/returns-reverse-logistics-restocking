<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VendorClaimStatus;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorClaim extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'rma_item_id',
        'supplier_name',
        'status',
        'claim_amount_cents',
        'evidence',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => VendorClaimStatus::class,
            'evidence' => 'array',
            'resolved_at' => 'datetime',
        ];
    }

    public function rmaItem(): BelongsTo
    {
        return $this->belongsTo(RmaItem::class);
    }
}
