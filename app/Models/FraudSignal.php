<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Rma;

class FraudSignal extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'order_id',
        'rma_id',
        'score',
        'signals',
        'status',
        'reviewed_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'signals' => 'array',
            'status' => \App\Enums\FraudSignalStatus::class,
            'reviewed_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function rma(): BelongsTo
    {
        return $this->belongsTo(Rma::class, 'rma_id');
    }
}
