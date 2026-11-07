<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Rma;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

final class TrackReturn extends Component
{
    public string $number = '';

    public string $email = '';

    public ?Rma $rma = null;

    public ?string $labelUrl = null;

    public function track(): void
    {
        $this->validate([
            'number' => ['required', 'string'],
            'email' => ['required', 'email'],
        ]);

        $this->rma = Rma::query()
            ->withoutGlobalScopes()
            ->where('number', $this->number)
            ->whereHas('customer', fn ($q) => $q->where('email', $this->email))
            ->with(['statusHistories', 'items.product'])
            ->first();

        if ($this->rma === null) {
            $this->addError('number', 'No RMA found.');

            return;
        }

        $this->labelUrl = URL::signedRoute('portal.label.print', ['rma' => $this->rma]);
    }

    public function render()
    {
        return view('livewire.portal.track-return')
            ->layout('layouts.portal');
    }
}
