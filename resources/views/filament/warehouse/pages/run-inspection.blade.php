<x-filament-panels::page>
    <div class="space-y-6 max-w-2xl">
        <form wire:submit="start">
            {{ $this->form }}
            <x-filament::button type="submit" class="mt-4">Start inspection</x-filament::button>
        </form>

        @if($inspection_id)
            <div class="flex flex-wrap gap-3">
                <x-filament::button wire:click="complete" color="success">Complete inspection</x-filament::button>
                <x-filament::button wire:click="assist" color="gray">AI assist</x-filament::button>
            </div>
        @endif

        @if($ai_summary)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 text-sm">
                <strong>AI summary</strong>
                <p class="mt-2">{{ $ai_summary }}</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
