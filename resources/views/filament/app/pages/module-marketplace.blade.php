<x-filament-panels::page>
    <div class="space-y-4">
        @foreach ($this->moduleRows as $module)
            <div
                wire:key="module-{{ $module['key'] }}"
                class="flex flex-col gap-2 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                        {{ $module['name'] }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $module['description'] }}
                    </p>
                    <p class="mt-1 font-mono text-xs text-gray-400">{{ $module['key'] }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <span @class([
                        'text-xs font-medium rounded-full px-2 py-1',
                        'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400' => $module['enabled'],
                        'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300' => ! $module['enabled'],
                    ])>
                        {{ $module['enabled'] ? 'Enabled' : 'Disabled' }}
                    </span>

                    @if (! $module['is_core'])
                        <x-filament::button
                            wire:click="toggleModule('{{ $module['key'] }}')"
                            color="{{ $module['enabled'] ? 'danger' : 'primary' }}"
                            size="sm"
                        >
                            {{ $module['enabled'] ? 'Disable' : 'Enable' }}
                        </x-filament::button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
