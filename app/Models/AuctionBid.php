<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Auction;

class AuctionBid extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'auction_id',
        'bidder_name',
        'bidder_email',
        'amount_cents',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'amount_cents' => 'integer',
            'meta' => 'array',
        ];
    }

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }
}
