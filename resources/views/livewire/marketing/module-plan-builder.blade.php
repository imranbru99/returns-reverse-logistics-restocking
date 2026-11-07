<div class="space-y-6">
    <p class="text-2xl font-semibold text-teal-600">${{ number_format($estimate) }} <span class="text-sm font-normal text-slate-500">/ month estimate</span></p>
    <div class="grid gap-3 md:grid-cols-2">
        @foreach ($modules as $module)
            @php($key = $module['key'])
            <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 p-3 dark:border-slate-800">
                <input type="checkbox" wire:click="toggle('{{ $key }}')" @checked($selected[$key] ?? false)>
                <span>
                    <span class="font-medium">{{ $module['name'] }}</span>
                    <span class="block text-xs text-slate-500">{{ $module['price_tier'] }} · ${{ $tierPrices[$module['price_tier']] ?? 0 }}</span>
                </span>
            </label>
        @endforeach
    </div>
</div>
