<?php

declare(strict_types=1);

namespace App\Services\StoreCredit;

use App\Enums\StoreCreditLedgerType;
use App\Models\StoreCreditAccount;
use App\Models\StoreCreditLedgerEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class CreditWalletService
{
    public function accountForCustomer(string $customerId, string $currency = 'USD'): StoreCreditAccount
    {
        return StoreCreditAccount::query()->firstOrCreate(
            [
                'customer_id' => $customerId,
                'currency' => $currency,
            ],
            [
                'balance_cents' => 0,
            ],
        );
    }

    public function credit(string $customerId, int $amountCents, string $currency, ?Model $reference = null): StoreCreditLedgerEntry
    {
        return $this->apply($customerId, $amountCents, $currency, StoreCreditLedgerType::Credit, $reference);
    }

    public function debit(string $customerId, int $amountCents, string $currency, ?Model $reference = null): StoreCreditLedgerEntry
    {
        return $this->apply($customerId, -$amountCents, $currency, StoreCreditLedgerType::Debit, $reference);
    }

    private function apply(
        string $customerId,
        int $signedAmountCents,
        string $currency,
        StoreCreditLedgerType $type,
        ?Model $reference,
    ): StoreCreditLedgerEntry {
        return DB::transaction(function () use ($customerId, $signedAmountCents, $currency, $type, $reference): StoreCreditLedgerEntry {
            $account = $this->accountForCustomer($customerId, $currency);
            $newBalance = $account->balance_cents + $signedAmountCents;

            if ($newBalance < 0) {
                throw new \InvalidArgumentException('Insufficient store credit balance.');
            }

            $account->forceFill(['balance_cents' => $newBalance])->save();

            return StoreCreditLedgerEntry::query()->create([
                'tenant_id' => $account->tenant_id,
                'store_credit_account_id' => $account->getKey(),
                'type' => $type,
                'amount_cents' => abs($signedAmountCents),
                'balance_after_cents' => $newBalance,
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->getKey(),
                'notes' => $type === StoreCreditLedgerType::Credit ? 'Credit issued' : 'Debit applied',
            ]);
        });
    }
}
