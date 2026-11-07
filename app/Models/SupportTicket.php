<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Customer;
use App\Models\Rma;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportTicket extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'rma_id',
        'customer_id',
        'number',
        'subject',
        'status',
        'csat_score',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\SupportTicketStatus::class,
            'csat_score' => 'integer',
            'meta' => 'array',
        ];
    }

    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class, 'rma_id');
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
