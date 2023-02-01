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
        <x-laravel-base::table.td class="bg-slate-200" colspan="{{ $colspan }}">

            @unless ($selectAll)
                <div class="space-x-1">
                    <span>{!! __('base::messages.table.page_selected', ['count' => $count, 'item_name' => $itemName]) !!}</span>

                    <x-blade::button.link wire:click="selectAll">
                        {!! __('base::messages.table.select_all_rows', ['total' => $total, 'item_name' => $itemName]) !!}
                    </x-blade::button.link>
                </div>
            @else
                <span class="block space-x-1">
                <span>{!! __('base::messages.table.all_rows_selected', ['total' => $total, 'item_name' => $itemName]) !!}</span>

                <x-blade::button.link wire:click="clearSelection">
                    {{ __('base::messages.table.clear_selection') }}
                </x-blade::button.link>
            </span>
            @endunless

        </x-laravel-base::table.td>
    </x-laravel-base::table.tr>
@endif
