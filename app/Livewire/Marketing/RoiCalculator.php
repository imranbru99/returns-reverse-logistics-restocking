<?php

declare(strict_types=1);

namespace App\Livewire\Marketing;

use Livewire\Component;

final class RoiCalculator extends Component
{
    public int $monthlyReturns = 1200;

    public float $avgItemValue = 48;

    public float $recoveryRateBefore = 42;

    public float $recoveryRateAfter = 58;

    public float $handlingCostPerUnit = 6.5;

    public function savingsMonthly(): float
    {
        $before = $this->monthlyReturns * $this->avgItemValue * ($this->recoveryRateBefore / 100);
        $after = $this->monthlyReturns * $this->avgItemValue * ($this->recoveryRateAfter / 100);

        return max(0, $after - $before);
    }

    public function netMonthly(): float
    {
        $handlingDelta = $this->monthlyReturns * max(0, 1.5);

        return $this->savingsMonthly() - $handlingDelta;
    }

    public function render()
    {
        return view('livewire.marketing.roi-calculator');
    }
}
