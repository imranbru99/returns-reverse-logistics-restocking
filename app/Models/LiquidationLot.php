<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Auction;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiquidationLot extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'status',
        'reserve_price_cents',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\LiquidationLotStatus::class,
            'reserve_price_cents' => 'integer',
            'meta' => 'array',
        ];
    }

    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class);
    }
}
