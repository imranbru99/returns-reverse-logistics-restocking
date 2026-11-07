<div class="mx-auto max-w-lg px-4 py-16">
    <h1 class="font-display text-4xl font-semibold">Start your trial</h1>
    <form wire:submit="register" class="mt-8 space-y-4">
        <input wire:model="company" placeholder="Company" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:border-slate-700 dark:bg-slate-900" required>
        <input wire:model="name" placeholder="Your name" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:border-slate-700 dark:bg-slate-900" required>
        <input wire:model="email" type="email" placeholder="Email" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:border-slate-700 dark:bg-slate-900" required>
        <input wire:model="password" type="password" placeholder="Password" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:border-slate-700 dark:bg-slate-900" required>
        <select wire:model="preset" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:border-slate-700 dark:bg-slate-900">
            @foreach ($presets as $case)
                <option value="{{ $case->value }}">{{ $case->label() }}</option>
            @endforeach
        </select>
        @error('email') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
        <button type="submit" class="rounded-lg bg-teal-600 px-4 py-2 text-white">Create tenant</button>
    </form>
</div>
