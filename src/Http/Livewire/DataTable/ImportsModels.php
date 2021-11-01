<?php

namespace Rawilk\LaravelBase\Http\Livewire\DataTable;

use Exception;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;

/**
 * @mixin \Livewire\Component
 */
trait ImportsModels
{
    public static string $showEvent = 'showImportModal';

    public bool $showImport = false;
    public $columns = [];
    public $upload;

    abstract protected function guesses(): array;

    abstract public function import(): void;

    abstract public static function importId(): string;

    public function showImportModal(string $importId = ''): void
    {
        if ($this::importId() === $importId) {
            $this->showImport = true;

            $this->updatedShowImport($this->showImport);
        }
    }

    public function updatingUpload($value): void
    {
        $this->resetErrorBag();

        // We are probably resetting...
        if (is_null($value)) {
            return;
        }

        Validator::make(['upload' => $value], [
            'upload' => ['required', 'mimes:' . $this->mimes()],
        ])->validate();
    }

    public function updatedShowImport($value): void
    {
        if ($value) {
            $this->reset('columns', 'upload');
            $this->dispatchBrowserEvent('file-pond-clear', ['id' => $this->id]);
            $this->resetErrorBag();
        }
    }

    public function updatedUpload(): void
    {
        $this->resetErrorBag();

        if (is_null($this->upload)) {
            return;
        }

        try {
            $this->columns = collect((new HeadingRowImport)->toArray($this->upload, '', '')[0][0])->filter();
        } catch (Exception) {
            $this->columns = collect();
        }

        $this->guessWhichColumnsMapToWhichFields();
    }

    protected function mimes(): string
    {
        return property_exists($this, 'mimes')
            ? $this->mimes
            : 'txt,csv,xls,xlsx';
    }

    protected function guessWhichColumnsMapToWhichFields(): void
    {
        // There's no point in doing this if we don't have a mapping defined.
        if (! property_exists($this, 'fieldColumnMap')) {
            return;
        }

        foreach ($this->columns as $column) {
            $match = collect($this->guesses())
                ->search(fn ($options) => in_array(strtolower($column), $options, true));

            if ($match) {
                $this->fieldColumnMap[$match] = $column;
            }
        }
    }

    public function initializeImportsModels(): void
    {
        $this->listeners = array_merge($this->listeners, [
            static::$showEvent,
        ]);
    }
}
