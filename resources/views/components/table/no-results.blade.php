@props([
    'colspan' => 1,
    'icon' => 'heroicon-o-circle-stack',
])

<x-laravel-base::table.tr>
    <x-laravel-base::table.td colspan="{{ $colspan }}">
        <div class="flex justify-center items-center space-x-2">
            <x-dynamic-component :component="$icon" class="h-9 w-9 text-slate-400" />

            <span class="font-medium py-8 text-slate-400 text-xl">
                {{ $slot }}
            </span>
        </div>
    </x-laravel-base::table.td>
</x-laravel-base::table.tr>
