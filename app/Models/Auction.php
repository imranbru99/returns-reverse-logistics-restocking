<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AuctionBid;
use App\Models\LiquidationLot;

class Auction extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'liquidation_lot_id',
        'starts_at',
        'ends_at',
        'status',
        'winning_bid_cents',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'status' => \App\Enums\AuctionStatus::class,
            'winning_bid_cents' => 'integer',
            'meta' => 'array',
        ];
    }

    public function liquidationLot(): BelongsTo
    {
        return $this->belongsTo(LiquidationLot::class, 'liquidation_lot_id');
    }
    public function bids(): HasMany
    {
        return $this->hasMany(AuctionBid::class);
    }
}
