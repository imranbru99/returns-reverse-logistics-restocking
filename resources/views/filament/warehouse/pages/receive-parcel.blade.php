<x-filament-panels::page>
    <form wire:submit="receive" class="space-y-6 max-w-xl">
        {{ $this->form }}

        <x-filament::button type="submit" size="lg" class="w-full">
            Confirm receive
        </x-filament::button>
    </form>
</x-filament-panels::page>
