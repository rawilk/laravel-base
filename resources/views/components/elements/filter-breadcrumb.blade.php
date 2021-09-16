@props(['breadcrumb'])

<div {{ $attributes->class('bg-blue-gray-200 px-2.5 py-2 rounded-md shadow-sm text-blue-gray-800 text-xs ml-3 first:ml-0 max-w-full flex items-center space-x-2') }}>
    <div class="truncate min-w-0">
        <span class="font-medium">{{ $breadcrumb['label'] }}:</span> {{ $breadcrumb['valueDisplay'] }}
    </div>

    <div>
        <button
            wire:click="removeFilter('{{ $breadcrumb['key'] }}', '{{ $breadcrumb['value'] }}')"
            type="button"
            title="{{ __('laravel-base::messages.remove_filter') }}"
            class="flex justify-center items-center focus:ring-0 focus:outline-blue-gray rounded-full hover:bg-blue-gray-300 p-1 group"
        >
            <span class="sr-only">{{ __('laravel-base::messages.remove_filter') }}</span>
            <x-css-close class="h-3 w-3 text-blue-gray-800 group-hover:text-blue-gray-500" />
        </button>
    </div>
</div>
