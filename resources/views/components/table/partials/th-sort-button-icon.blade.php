@if ($direction === 'asc')
    <x-heroicon-s-chevron-up class="w-4 h-4 group-hover:opacity-0" />
    <x-heroicon-s-chevron-down class="w-4 h-4 opacity-0 group-hover:opacity-100 absolute" />
@elseif ($direction === 'desc')
    <div class="opacity-0 group-hover:opacity-100 absolute">
        <x-heroicon-s-arrows-up-down class="w-4 h-4" />
    </div>

    <x-heroicon-s-chevron-down class="w-4 h-4 group-hover:opacity-0" />
@else
    <x-heroicon-s-chevron-up class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
@endif
