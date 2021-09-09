<?php

namespace Rawilk\LaravelBase\Http\Livewire\DataTable;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

/**
 * @property-read \Illuminate\Support\Collection $filterBreadcrumbs
 * @mixin \Livewire\Component
 * @mixin \Livewire\WithPagination
 */
trait WithFiltering
{
    public bool $showFilters = false;

    protected function filters(): array
    {
        return property_exists($this, 'filters')
            ? $this->filters
            : [];
    }

    public function getFilterBreadcrumbsProperty(): Collection
    {
        return collect($this->filters())
            ->reject(fn ($value) => empty($value) && $value !== 0 && $value !== '0')
            ->map(function ($value, $key) {
                $valueDisplay = method_exists($this, 'mapFilterValue')
                    ? $this->mapFilterValue($key, $value)
                    : $value;

                return [
                    'key' => $key,
                    'value' => $value,
                    'valueDisplay' => $valueDisplay,
                    'label' => $key,
                ];
            });
    }

    public function removeFilter($key, $value): void
    {
        if (! property_exists($this, 'filters')) {
            return;
        }

        if (! array_key_exists($key, $this->filters)) {
            return;
        }

        if (is_array($this->filters[$key])) {
            $this->filters[$key] = collect($this->filters[$key])
                // Using a 'loose' comparison on purpose here.
                ->reject(fn ($filterValue) => $filterValue == $value)
                ->values()
                ->toArray();

            return;
        }

        // This is based off how Livewire resets a property in the `reset` method.
        $freshInstance = new static($this->id);

        $this->filters[$key] = $freshInstance->filters[$key];
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
    }

    public function toggleShowFilters(): void
    {
        if (method_exists($this, 'useCachedRows')) {
            $this->useCachedRows();
        }

        $this->showFilters = ! $this->showFilters;

        if (! $this->showFilters) {
            $this->emit('filters-hidden');
        }
    }

    public function updatedFilters(): void
    {
        $this->resetPage();

        if ($this->showFilters) {
            $this->emitSelf('filters-applied');
        }
    }

    protected function localizeMinDate($date): null|CarbonInterface
    {
        return minDateToUTC($date);
    }

    protected function localizeMaxDate($date): null|CarbonInterface
    {
        return maxDateToUTC($date);
    }
}
