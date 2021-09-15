@props([
    'colspan' => 1,
    'selectPage' => false,
    'selectAll' => false,
    'count' => 0,
    'itemName' => 'rows',
    'total' => 0,
])

@if ($selectPage)
<x-laravel-base::table.tr wire:key="row-message">
    <x-laravel-base::table.td class="bg-blue-gray-200" colspan="{{ $colspan }}">

        @unless ($selectAll)
            <div class="space-x-1">
                <span>{!! __('laravel-base::messages.table.page_selected', ['count' => $count, 'item_name' => $itemName]) !!}</span>

                <x-laravel-base::button.link :supports-icons="false" wire:click="selectAll">
                    {!! __('laravel-base::messages.table.select_all_rows', ['total' => $total, 'item_name' => $itemName]) !!}
                </x-laravel-base::button.link>
            </div>
        @else
            <span class="block space-x-1">
                <span>{!! __('laravel-base::messages.table.all_rows_selected', ['total' => $total, 'item_name' => $itemName]) !!}</span>

                <x-laravel-base::button.link wire:click="clearSelection">
                    {{ __('laravel-base::messages.table.clear_selection') }}
                </x-laravel-base::button.link>
            </span>
        @endunless

    </x-laravel-base::table.td>
</x-laravel-base::table.tr>
@endif
