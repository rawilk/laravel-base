<?php

namespace Rawilk\LaravelBase\Http\Livewire\DataTable;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
                $method = 'mapFilter' . Str::studly($key) . 'Value';

                if (method_exists($this, $method)) {
                    $valueDisplay = $this->{$method}($value);
                } else {
                    $valueDisplay = method_exists($this, 'mapFilterValue')
                        ? $this->mapFilterValue($key, $value)
                        : $value;
                }

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

        $callbackIsDefined = method_exists($this, 'onFilterRemoved');

        if (is_array($this->filters[$key])) {
            $this->filters[$key] = collect($this->filters[$key])
                // Using a 'loose' comparison on purpose here.
                ->reject(fn ($filterValue) => $filterValue == $value)
                ->values()
                ->toArray();

            if ($callbackIsDefined) {
                $this->onFilterRemoved($key, $value, $this->filters[$key]);
            }

            return;
        }

        // This is based off how Livewire resets a property in the `reset` method.
        $freshInstance = new static($this->id);

        $this->filters[$key] = $freshInstance->filters[$key];

        if ($callbackIsDefined) {
            $this->onFilterRemoved($key, $value, $this->filters[$key]);
        }
    }

    public function resetFilters(): void
    {
        $this->reset('filters');
        $this->emit('filters-reset');

        if (method_exists($this, 'onFiltersReset')) {
            $this->onFiltersReset();
        }
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

    /*
     * Dummy method for triggering a $refresh on the component. Could've just
     * used $wire.$refresh() in the UI, but this allows us to show the
     * loading indicator on the button as well. Could also override
     * this method for custom filtering logic in components too.
     */
    public function applyFilters(): void
    {
        $this->emitSelf('filters-applied');
    }

    public function updatedFilters($value = null, $key = null): void
    {
        $this->resetPage();

        if (method_exists($this, 'onFiltersUpdated')) {
            $this->onFiltersUpdated($value, $key);
        }

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
