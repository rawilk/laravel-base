<?php

namespace Rawilk\LaravelBase\Http\Livewire\DataTable;

trait HasDataTable
{
    use WithPerPagePagination;
    use WithSorting;
    use WithBulkActions;
    use HidesColumns;
    use WithFiltering;
}
