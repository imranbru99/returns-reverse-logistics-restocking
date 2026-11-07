<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\RecyclingPartner;
use Illuminate\Database\Eloquent\SoftDeletes;

class DestructionCertificate extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'recycling_partner_id',
        'certificate_number',
        'issued_at',
        'pdf_path',
        'items',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'items' => 'array',
            'meta' => 'array',
        ];
    }

    public function recyclingPartner(): BelongsTo
    {
        return $this->belongsTo(RecyclingPartner::class, 'recycling_partner_id');
    }
}
