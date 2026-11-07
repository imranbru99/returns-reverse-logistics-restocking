<div class="space-y-4 rounded-xl border border-slate-200 p-6 dark:border-slate-800">
    <label class="block text-sm">Monthly returns
        <input type="number" wire:model.live="monthlyReturns" class="mt-1 w-full rounded border border-slate-300 px-2 py-1 dark:border-slate-700 dark:bg-slate-900">
    </label>
    <label class="block text-sm">Average item value ($)
        <input type="number" step="0.01" wire:model.live="avgItemValue" class="mt-1 w-full rounded border border-slate-300 px-2 py-1 dark:border-slate-700 dark:bg-slate-900">
    </label>
    <label class="block text-sm">Recovery rate before (%)
        <input type="number" step="0.1" wire:model.live="recoveryRateBefore" class="mt-1 w-full rounded border border-slate-300 px-2 py-1 dark:border-slate-700 dark:bg-slate-900">
    </label>
    <label class="block text-sm">Recovery rate after (%)
        <input type="number" step="0.1" wire:model.live="recoveryRateAfter" class="mt-1 w-full rounded border border-slate-300 px-2 py-1 dark:border-slate-700 dark:bg-slate-900">
    </label>
    <p class="text-lg font-semibold text-teal-600">Estimated net monthly uplift: ${{ number_format($this->netMonthly(), 2) }}</p>
</div>
