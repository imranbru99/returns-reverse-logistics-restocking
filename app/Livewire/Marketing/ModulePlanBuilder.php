<?php

declare(strict_types=1);

namespace App\Livewire\Marketing;

use App\Support\MarketingCatalog;
use Livewire\Component;

final class ModulePlanBuilder extends Component
{
    /** @var array<string, bool> */
    public array $selected = [];

    public function mount(): void
    {
        foreach (MarketingCatalog::modules() as $module) {
            $key = (string) ($module['key'] ?? '');
            if ($key === '') {
                continue;
            }

            $this->selected[$key] = (bool) ($module['default_enabled'] ?? false);
        }
    }

    public function toggle(string $key): void
    {
        if (! array_key_exists($key, $this->selected)) {
            return;
        }

        $this->selected[$key] = ! $this->selected[$key];
    }

    public function estimatedMonthly(): int
    {
        $tierPrices = config('plans.tier_prices', []);
        $total = 0;

        foreach (MarketingCatalog::modules() as $module) {
            $key = (string) ($module['key'] ?? '');

            if ($key === '' || empty($this->selected[$key])) {
                continue;
            }

            $tier = (string) ($module['price_tier'] ?? 'starter');
            $total += (int) ($tierPrices[$tier] ?? 0);
        }

        return $total;
    }

    public function render()
    {
        return view('livewire.marketing.module-plan-builder', [
            'modules' => MarketingCatalog::modules(),
            'estimate' => $this->estimatedMonthly(),
            'tierPrices' => config('plans.tier_prices', []),
        ]);
    }
}
