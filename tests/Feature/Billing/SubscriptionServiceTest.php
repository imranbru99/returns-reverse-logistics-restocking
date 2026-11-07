<?php

declare(strict_types=1);

use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\UsageMeter;
use App\Services\Billing\SubscriptionService;

it('starts a trial subscription for a tenant', function (): void {
    $tenant = Tenant::factory()->create();
    $plan = Plan::factory()->create(['key' => 'starter-test']);

    $subscription = app(SubscriptionService::class)->startTrial($tenant, $plan, 7);

    expect($subscription->status)->toBe(SubscriptionStatus::Trialing)
        ->and($tenant->fresh()->trial_ends_at)->not->toBeNull()
        ->and($subscription->plan_id)->toBe($plan->getKey());
});

it('meters RMA usage for the current billing period', function (): void {
    $tenant = Tenant::factory()->create();
    $plan = Plan::factory()->create();
    app(SubscriptionService::class)->startTrial($tenant, $plan);

    $meter = app(SubscriptionService::class)->meterRmaUsage($tenant, 3);

    expect($meter->metric)->toBe(UsageMeter::METRIC_RMAS)
        ->and($meter->quantity)->toBe(3);
});
