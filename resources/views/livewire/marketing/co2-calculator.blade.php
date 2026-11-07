<div class="space-y-4 rounded-xl border border-slate-200 p-6 dark:border-slate-800">
    <label class="block text-sm">Monthly returns
        <input type="number" wire:model.live="monthlyReturns" class="mt-1 w-full rounded border border-slate-300 px-2 py-1 dark:border-slate-700 dark:bg-slate-900">
    </label>
    <label class="block text-sm">Diversion rate (%)
        <input type="number" step="0.1" wire:model.live="diversionRate" class="mt-1 w-full rounded border border-slate-300 px-2 py-1 dark:border-slate-700 dark:bg-slate-900">
    </label>
    <p class="text-lg font-semibold text-teal-600">CO₂ avoided: {{ number_format($this->co2SavedKg(), 1) }} kg / month</p>
    <p class="text-sm text-slate-500">{{ $this->divertedReturns() }} returns diverted from landfill</p>
</div>
