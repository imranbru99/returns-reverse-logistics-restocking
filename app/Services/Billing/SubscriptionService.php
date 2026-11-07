<?php

declare(strict_types=1);

namespace App\Services\Billing;

use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\UsageMeter;
use Illuminate\Support\Carbon;

final class SubscriptionService
{
    public function startTrial(Tenant $tenant, Plan $plan, int $trialDays = 14): Subscription
    {
        $trialEnds = now()->addDays($trialDays);

        $subscription = Subscription::query()->firstOrNew([
            'tenant_id' => $tenant->getKey(),
        ]);

        $subscription->fill([
            'plan_id' => $plan->getKey(),
            'trial_ends_at' => $trialEnds,
            'starts_at' => now(),
            'status' => SubscriptionStatus::Trialing,
        ])->save();

        $tenant->forceFill(['trial_ends_at' => $trialEnds])->save();

        return $subscription;
    }

    public function meterRmaUsage(Tenant $tenant, int $quantity = 1): UsageMeter
    {
        [$start, $end] = $this->currentBillingPeriod();

        $subscription = Subscription::query()
            ->where('tenant_id', $tenant->getKey())
            ->whereIn('status', [SubscriptionStatus::Trialing, SubscriptionStatus::Active])
            ->latest()
            ->first();

        $meter = UsageMeter::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->getKey(),
                'metric' => UsageMeter::METRIC_RMAS,
                'period_start' => $start,
                'period_end' => $end,
            ],
            [
                'subscription_id' => $subscription?->getKey(),
                'quantity' => 0,
            ],
        );

        $meter->increment('quantity', $quantity);

        return $meter->fresh();
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function currentBillingPeriod(): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        return [$start, $end];
    }

    public function ensureStarterPlan(): Plan
    {
        $config = config('plans.plans.starter', []);

        return Plan::query()->firstOrCreate(
            ['key' => 'starter'],
            [
                'name' => $config['name'] ?? 'Starter',
                'price' => $config['price'] ?? 199,
                'currency' => config('plans.currency', 'USD'),
                'limits' => $config['limits'] ?? [],
                'included_modules' => $config['included_modules'] ?? [],
                'is_active' => true,
            ],
        );
    }
}
