<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * @property-read \Illuminate\Support\Collection $imports
 */
class CsvImports extends Component
{
    public string $model;

    public string $importClass;

    protected $listeners = [
        'imports.refresh' => '$refresh',
    ];

    public function getImportsProperty(): Collection
    {
        return Auth::user()
            ->imports()
            ->oldest()
            ->notCompleted()
            ->forModel($this->model)
            ->forImport($this->importClass)
            ->get();
    }

    public function render(): View
    {
        return view('laravel-base::livewire.imports.csv-imports');
    }
}
