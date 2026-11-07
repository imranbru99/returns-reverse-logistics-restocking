<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\StoreCreditAccount;

class StoreCreditLedgerEntry extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'store_credit_account_id',
        'type',
        'amount_cents',
        'balance_after_cents',
        'reference_type',
        'reference_id',
        'notes',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'type' => \App\Enums\StoreCreditLedgerType::class,
            'amount_cents' => 'integer',
            'balance_after_cents' => 'integer',
            'meta' => 'array',
        ];
    }

    public function storeCreditAccount(): BelongsTo
    {
        return $this->belongsTo(StoreCreditAccount::class, 'store_credit_account_id');
    }
}
