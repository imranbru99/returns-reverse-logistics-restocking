<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\WarrantyRegistration;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarrantyClaim extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'warranty_registration_id',
        'number',
        'status',
        'description',
        'resolved_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\WarrantyClaimStatus::class,
            'resolved_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function warrantyRegistration(): BelongsTo
    {
        return $this->belongsTo(WarrantyRegistration::class, 'warranty_registration_id');
    }
}
