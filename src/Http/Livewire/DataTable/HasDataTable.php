<?php

namespace Rawilk\LaravelBase\Http\Livewire\DataTable;

trait HasDataTable
{
    use WithPerPagePagination,
        WithSorting,
        WithBulkActions,
        HidesColumns,
        WithFiltering;
}
