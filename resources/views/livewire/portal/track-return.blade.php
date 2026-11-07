<div>
    <h1 class="font-display text-3xl font-semibold">Track return</h1>
    <form wire:submit="track" class="mt-8 space-y-4">
        <input wire:model="number" placeholder="RMA number" class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2" required>
        <input wire:model="email" type="email" placeholder="Email" class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2" required>
        <button class="rounded-lg bg-teal-600 px-4 py-2 text-white">Track</button>
    </form>
    @if ($rma)
        <div class="mt-8 rounded-xl border border-slate-800 p-4">
            <p class="font-semibold">{{ $rma->number }}</p>
            <p class="text-sm text-slate-300">Status: {{ $rma->status->label() }}</p>
            @if ($labelUrl)
                <a href="{{ $labelUrl }}" class="mt-3 inline-block text-teal-400 underline">Print label</a>
            @endif
        </div>
    @endif
</div>
