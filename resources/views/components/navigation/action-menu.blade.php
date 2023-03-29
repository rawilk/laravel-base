<x-laravel-base::navigation.dropdown
    :right="$right"
    :fixed="$fixed"
    :id="$id"
    {{ $attributes }}
>
    <x-slot:trigger>
        <button class="p-2 hover:bg-slate-200 dark:hover:bg-slate-500 text-slate-500 dark:hover:text-slate-300 rounded-full focus:outline-slate focus:opacity-75 transition-colors">
            <x-dynamic-component
                :component="$icon"
                class="h-5 w-5"
            />
        </button>
    </x-slot:trigger>

    {{ $slot }}
</x-laravel-base::navigation.dropdown>
