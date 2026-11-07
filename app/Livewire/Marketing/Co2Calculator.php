<?php

declare(strict_types=1);

namespace App\Livewire\Marketing;

use Livewire\Component;

final class Co2Calculator extends Component
{
    public int $monthlyReturns = 800;

    public float $diversionRate = 35;

    public float $kgCo2PerReturn = 2.4;

    public function divertedReturns(): int
    {
        return (int) round($this->monthlyReturns * ($this->diversionRate / 100));
    }

    public function co2SavedKg(): float
    {
        return $this->divertedReturns() * $this->kgCo2PerReturn;
    }

    public function render()
    {
        return view('livewire.marketing.co2-calculator');
    }
}
