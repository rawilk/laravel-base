<?php

namespace Rawilk\LaravelBase\Http\Livewire\DataTable;

use Illuminate\Support\Str;

trait WithSorting
{
    public array $sorts = [];

    public function sortBy(string $field)
    {
        if (! isset($this->sorts[$field])) {
            return $this->sorts[$field] = 'asc';
        }

        if ($this->sorts[$field] === 'asc') {
            return $this->sorts[$field] = 'desc';
        }

        unset($this->sorts[$field]);
    }

    public function applySorting($query)
    {
        foreach ($this->sorts as $field => $direction) {
            $sortMethod = 'sort' . Str::studly($field);

            if (method_exists($this, $sortMethod)) {
                $this->{$sortMethod}($query, $direction);
            } else {
                $query->orderBy($field, $direction);
            }
        }

        return $query;
    }
}
