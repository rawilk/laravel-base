@if ($direction)
    aria-sort="{{ $direction === 'asc' ? 'ascending' : 'descending' }}"

    @if ($direction === 'desc')
        aria-label="{{ __('laravel-base::messages.table.clear_sort') }}"
        title="{{ __('laravel-base::messages.table.clear_sort') }}"
    @else
        aria-label="{{ __('laravel-base::messages.table.click_to_sort', ['direction' => $direction === 'asc' ? 'Descending' : 'Ascending']) }}"
        title="{{ __('laravel-base::messages.table.click_to_sort', ['direction' => $direction === 'asc' ? 'Descending' : 'Ascending']) }}"
    @endif
@else
    aria-sort="none"
    aria-label="{{ __('laravel-base::messages.table.click_to_sort', ['direction' => 'Ascending']) }}"
    title="{{ __('laravel-base::messages.table.click_to_sort', ['direction' => 'Ascending']) }}"
@endif
