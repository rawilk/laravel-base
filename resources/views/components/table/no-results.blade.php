@props([
    'colspan' => 1,
    'icon' => 'heroicon-o-database',
])

<x-laravel-base::table.tr>
    <x-laravel-base::table.td colspan="{{ $colspan }}">
        <div class="flex justify-center items-center space-x-2">
            <x-dynamic-component :component="$icon" class="h-9 w-9 text-blue-gray-400" />

            <span class="font-medium py-8 text-blue-gray-400 text-xl">
                {{ $slot }}
            </span>
        </div>
    </x-laravel-base::table.td>
</x-laravel-base::table.tr>
