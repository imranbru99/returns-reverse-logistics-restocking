<?php

declare(strict_types=1);

namespace App\Services\Revenue;

use App\Enums\AuctionStatus;
use App\Models\Auction;
use App\Models\AuctionBid;
use Illuminate\Support\Facades\DB;

final class PlaceAuctionBidAction
{
    public function execute(Auction $auction, string $bidderName, int $amountCents, ?string $bidderEmail = null): AuctionBid
    {
        if ($auction->status !== AuctionStatus::Live && $auction->status !== AuctionStatus::Scheduled) {
            throw new \InvalidArgumentException('Auction is not accepting bids.');
        }

        if ($amountCents <= ($auction->winning_bid_cents ?? 0)) {
            throw new \InvalidArgumentException('Bid must exceed the current winning amount.');
        }

        return DB::transaction(function () use ($auction, $bidderName, $amountCents, $bidderEmail): AuctionBid {
            if ($auction->status === AuctionStatus::Scheduled && $auction->starts_at->isPast()) {
                $auction->forceFill(['status' => AuctionStatus::Live])->save();
            }

            $bid = AuctionBid::query()->create([
                'tenant_id' => $auction->tenant_id,
                'auction_id' => $auction->getKey(),
                'bidder_name' => $bidderName,
                'bidder_email' => $bidderEmail,
                'amount_cents' => $amountCents,
            ]);

            $auction->forceFill(['winning_bid_cents' => $amountCents])->save();

            return $bid;
        });
    }
}
