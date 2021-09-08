<?php

namespace Rawilk\LaravelBase\Http\Livewire\DataTable;

/**
 * @property-read bool $hasSelection
 * @property-read \Illuminate\Database\Eloquent\Collection $rows
 * @property-read \Illuminate\Database\Query\Builder $rowsQuery
 * @property-read \Illuminate\Database\Query\Builder $selectedRowsQuery
 */
trait WithBulkActions
{
    public bool $selectPage = false;
    public bool $selectAll = false;
    public $selected = [];

    public function getHasSelectionProperty(): bool
    {
        return count($this->selected) > 0;
    }

    public function getSelectedRowsQueryProperty()
    {
        return (clone $this->rowsQuery)
            ->unless($this->selectAll, fn ($query) => $query->whereKey($this->selected));
    }

    public function renderingWithBulkActions(): void
    {
        if ($this->selectAll) {
            $this->selectPageRows();
        }
    }

    public function updatedSelected(): void
    {
        $this->selectAll = false;
        $this->selectPage = false;
    }

    public function updatedSelectPage(bool $selectPage)
    {
        if ($selectPage) {
            return $this->selectPageRows();
        }

        $this->selectAll = false;
        $this->selected = [];
    }

    public function selectPageRows(): void
    {
        $this->selected = $this->rows->pluck('id')->map(fn ($id) => (string) $id);
    }

    public function selectAll(): void
    {
        $this->selectAll = true;
    }

    public function clearSelection(): void
    {
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function isSelected($key): bool
    {
        return is_array($this->selected)
            ? in_array($key, $this->selected, false)
            : $this->selected->contains($key);
    }
}
