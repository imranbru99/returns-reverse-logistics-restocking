<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\MarketplaceListing;

class MarketplaceOrder extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'marketplace_listing_id',
        'buyer_email',
        'buyer_name',
        'total_cents',
        'currency',
        'status',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'total_cents' => 'integer',
            'status' => \App\Enums\MarketplaceOrderStatus::class,
            'meta' => 'array',
        ];
    }

    public function marketplaceListing(): BelongsTo
    {
        return $this->belongsTo(MarketplaceListing::class, 'marketplace_listing_id');
    }
}
