<?php

namespace Rawilk\LaravelBase\Http\Livewire\DataTable;

/**
 * @property-read int $visibleColumns
 */
trait HidesColumns
{
    public array $hidden = [];

    public array $hideableColumns = [];

    /*
     * In most tables we create, there is usually a checkbox column, and an 'actions'
     * column, which should always be visible. If your table's columns differ,
     * override this method in your livewire component.
     */
    public function alwaysVisibleColumns(): int
    {
        return 2;
    }

    public function getVisibleColumnsProperty(): int
    {
        return ($this->alwaysVisibleColumns() + count($this->hideableColumns)) - count($this->hidden);
    }

    public function toggleColumn(string $column)
    {
        if ($this->isHidden($column)) {
            return $this->unhideColumn($column);
        }

        $this->hidden[] = $column;
    }

    public function unhideColumn(string $column): void
    {
        if (($key = array_search($column, $this->hidden, true)) !== false) {
            unset($this->hidden[$key]);
        }
    }

    public function isHidden(string $column): bool
    {
        return in_array($column, $this->hidden, true);
    }
}
