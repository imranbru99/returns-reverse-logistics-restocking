<x-filament-panels::page>
    <div class="grid gap-6 md:grid-cols-2">
        <x-filament::section heading="Recovery rate">
            <p class="text-3xl font-semibold">{{ number_format(($summary['recovery_rate'] ?? 0) * 100, 1) }}%</p>
            <p class="text-sm text-gray-500">Recoverable dispositions / total (30 days)</p>
        </x-filament::section>

        <x-filament::section heading="Dwell time">
            <p class="text-3xl font-semibold">{{ $summary['dwell']['average_hours'] ?? '—' }}<span class="text-lg">h</span></p>
            <p class="text-sm text-gray-500">Sample: {{ $summary['dwell']['sample_size'] ?? 0 }} inspections</p>
        </x-filament::section>

        <x-filament::section heading="Top return reasons" class="md:col-span-2">
            <ul class="list-disc pl-5 text-sm">
                @forelse ($summary['reasons'] ?? [] as $row)
                    <li>{{ $row['reason'] }} — {{ $row['count'] }}</li>
                @empty
                    <li>No RMA reason data in range.</li>
                @endforelse
            </ul>
        </x-filament::section>
    </div>
</x-filament-panels::page>
