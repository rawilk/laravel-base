<x-laravel-base::navigation.dropdown
    :right="$right"
    :fixed="$fixed"
    :id="$id"
    {{ $attributes }}
>
    <x-slot name="trigger">
        <button class="p-2 hover:bg-blue-gray-200 rounded-full focus:outline-blue-gray focus:opacity-75 transition-colors">
            <x-dynamic-component
                :component="$icon"
                class="h-5 w-5 text-blue-gray-500"
            />
        </button>
    </x-slot>

    {{ $slot }}
</x-laravel-base::navigation.dropdown>
