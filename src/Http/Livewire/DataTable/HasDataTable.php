<?php

namespace Rawilk\LaravelBase\Http\Livewire\DataTable;

trait HasDataTable
{
    use HidesColumns;
    use WithBulkActions;
    use WithFiltering;
    use WithPerPagePagination;
    use WithSorting;
}
