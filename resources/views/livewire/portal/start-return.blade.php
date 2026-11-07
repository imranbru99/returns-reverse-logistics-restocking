<div>
    <h1 class="font-display text-3xl font-semibold">Start a return</h1>
    @if ($step === 1)
        <form wire:submit="lookupOrder" class="mt-8 space-y-4">
            <input wire:model="orderNumber" placeholder="Order number" class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2" required>
            <input wire:model="email" type="email" placeholder="Email on order" class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2" required>
            @error('orderNumber') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
            <button class="rounded-lg bg-teal-600 px-4 py-2 text-white">Find order</button>
        </form>
    @elseif ($step === 2 && $order)
        <form wire:submit="submit" class="mt-8 space-y-4">
            @foreach ($order->items as $item)
                <label class="flex items-center gap-2 rounded border border-slate-800 p-3">
                    <input type="checkbox" wire:model="selectedItems.{{ $item->getKey() }}">
                    <span>{{ $item->product?->name ?? 'Item' }} (qty {{ $item->quantity }})</span>
                </label>
                <input wire:model="lineReasons.{{ $item->getKey() }}" placeholder="Line reason" class="w-full rounded border border-slate-700 bg-slate-900 px-2 py-1 text-sm">
            @endforeach
            <input wire:model="reason" placeholder="Return reason" class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2" required>
            <select wire:model="resolution" class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2">
                <option value="refund">Refund</option>
                <option value="exchange">Exchange</option>
                <option value="store_credit">Store credit</option>
            </select>
            <input type="file" wire:model="photos" multiple accept="image/*" class="text-sm">
            <button class="rounded-lg bg-teal-600 px-4 py-2 text-white">Submit RMA</button>
        </form>
    @elseif ($step === 4)
        <p class="mt-6 text-teal-300">RMA submitted.</p>
        @if ($labelUrl)
            <a href="{{ $labelUrl }}" class="mt-4 inline-block text-teal-400 underline">Print return label</a>
        @endif
    @endif
</div>
