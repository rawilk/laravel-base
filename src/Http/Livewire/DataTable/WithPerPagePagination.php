<?php

namespace Rawilk\LaravelBase\Http\Livewire\DataTable;

use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

trait WithPerPagePagination
{
    use WithPagination;

    public int $perPage = 10;

    protected function perPageSessionKey(): string
    {
        return 'perPage';
    }

    protected function perPageOptions(): array
    {
        /** @psalm-suppress UndefinedThisPropertyFetch */
        return property_exists($this, 'perPageOptions')
            ? $this->perPageOptions
            : [10, 25, 50];
    }

    public function initializeWithPerPagePagination(): void
    {
        $this->perPage = Session::get($this->perPageSessionKey(), $this->perPage);

        if (! in_array($this->perPage, $this->perPageOptions(), false)) {
            $this->perPage = $this->perPageOptions()[0] ?? 10;
        }
    }

    public function updatedPerPage(int $perPage): void
    {
        Session::put($this->perPageSessionKey(), $perPage);

        $this->resetPage();
    }

    public function applyPagination($query)
    {
        return $query->paginate($this->perPage);
    }
}
