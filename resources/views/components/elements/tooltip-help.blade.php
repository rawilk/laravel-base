<x-laravel-base::elements.tooltip
    :title="$title"
    :placement="$placement"
    :triggers="$triggers"
>
    <span tabindex="-1"
          class="text-xs cursor-help text-slate-500 hover:text-slate-400"
    >
        <x-css-info class="{{ $iconHeight }} {{ $iconWidth }}" />
    </span>
</x-laravel-base::elements.tooltip>
