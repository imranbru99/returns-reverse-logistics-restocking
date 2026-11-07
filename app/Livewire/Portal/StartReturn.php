<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Contracts\ScansUploadedFiles;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Rma\CreateRmaAction;
use App\Support\TenantContext;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Livewire\WithFileUploads;

final class StartReturn extends Component
{
    use WithFileUploads;

    public int $step = 1;

    public string $orderNumber = '';

    public string $email = '';

    public ?string $orderId = null;

    /** @var array<string, bool> */
    public array $selectedItems = [];

    /** @var array<string, string> */
    public array $lineReasons = [];

    public string $reason = '';

    public string $resolution = 'refund';

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $photos = [];

    public ?string $completedRmaId = null;

    public ?string $labelUrl = null;

    public function lookupOrder(): void
    {
        $this->validate([
            'orderNumber' => ['required', 'string'],
            'email' => ['required', 'email'],
        ]);

        $order = Order::query()
            ->withoutGlobalScopes()
            ->where('number', $this->orderNumber)
            ->whereHas('customer', fn ($q) => $q->where('email', $this->email))
            ->with(['items.product', 'customer'])
            ->first();

        if ($order === null) {
            $this->addError('orderNumber', 'Order not found for that email.');

            return;
        }

        $this->orderId = $order->getKey();
        TenantContext::set($order->tenant);
        $this->step = 2;
    }

    public function submit(CreateRmaAction $createRma, ScansUploadedFiles $virusScanner): void
    {
        if ($this->orderId === null) {
            return;
        }

        $order = Order::query()->withoutGlobalScopes()->with('items')->findOrFail($this->orderId);

        TenantContext::set($order->tenant);

        $lines = [];
        foreach ($order->items as $item) {
            if (empty($this->selectedItems[$item->getKey()])) {
                continue;
            }

            $lines[] = [
                'order_item_id' => $item->getKey(),
                'quantity' => 1,
                'reason' => $this->lineReasons[$item->getKey()] ?? $this->reason,
            ];
        }

        if ($lines === []) {
            $this->addError('selectedItems', 'Select at least one item.');

            return;
        }

        $this->validate([
            'reason' => ['required', 'string', 'max:500'],
            'photos.*' => ['nullable', 'image', 'max:4096'],
        ]);

        $rma = $createRma->execute(
            $order,
            $lines,
            $this->reason,
            'Preferred resolution: '.$this->resolution,
        );

        foreach ($this->photos as $photo) {
            $virusScanner->scan($photo);
            $rma->addMedia($photo->getRealPath())
                ->usingFileName($photo->getClientOriginalName())
                ->toMediaCollection('customer_photos');
        }

        $this->completedRmaId = $rma->getKey();
        $this->labelUrl = URL::signedRoute('portal.label.print', ['rma' => $rma]);
        $this->step = 4;
    }

    public function render()
    {
        $order = null;

        if ($this->orderId !== null) {
            $order = Order::query()->withoutGlobalScopes()->with('items.product')->find($this->orderId);
        }

        return view('livewire.portal.start-return', compact('order'))
            ->layout('layouts.portal');
    }
}
