<x-laravel-base::elements.tooltip
    :title="$title"
    :placement="$placement"
    :triggers="$triggers"
>
    <span tabindex="-1"
          class="text-xs cursor-help text-blue-gray-500 hover:text-blue-gray-400"
    >
        <x-css-info class="{{ $iconHeight }} {{ $iconWidth }}" />
    </span>
</x-laravel-base::elements.tooltip>
