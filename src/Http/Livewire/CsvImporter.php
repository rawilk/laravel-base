<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\TabularDataReader;
use Livewire\Component;
use Livewire\WithFileUploads;
use Rawilk\LaravelBase\Contracts\Models\Import;
use Rawilk\LaravelBase\Enums\Filesize as FilesizeEnum;
use Rawilk\LaravelBase\Events\Imports\ImportFinishedEvent;
use Rawilk\LaravelBase\Imports\GeneralImport;
use Rawilk\LaravelBase\Jobs\ImportCsvJob;
use Rawilk\LaravelBase\Services\Files\Filesize;
use Rawilk\LaravelBase\Support\Imports\ChunkIterator;

/**
 * @property-read \League\Csv\TabularDataReader $csvRecords
 * @property-read \Rawilk\LaravelBase\Services\Files\Filesize $maxFileSize
 * @property-read \League\Csv\Reader $readCsv
 */
class CsvImporter extends Component
{
    use WithFileUploads;

    public bool $open = false;

    public $file;

    public array $fileHeaders = [];

    public int $fileRowCount = 0;

    public array $columnsToMap = [];

    public array $requiredColumns = [];

    public array $columnLabels = [];

    protected $listeners = ['toggle'];

    public int $maxFileSizeInMb = 50;

    public string $model;

    public string $importClass = GeneralImport::class;

    public int $chunkSize = 500;

    /*
     * Define an array of guesses for auto-populating column mapping.
     */
    public array $guesses = [];

    public array $importExtras = [];

    public function getReadCsvProperty(): Reader
    {
        return $this->readCsv($this->file->getRealPath());
    }

    public function getMaxFileSizeProperty(): Filesize
    {
        return Filesize::of((string) $this->maxFileSizeInMb, FilesizeEnum::MegaByte);
    }

    public function getCsvRecordsProperty(): TabularDataReader
    {
        return Statement::create()->process($this->readCsv);
    }

    public function rules(): array
    {
        $columnRules = collect($this->requiredColumns)
            ->mapWithKeys(function (string $column) {
                return ["columnsToMap.{$column}" => ['required']];
            })
            ->toArray();

        return [
            'file' => [
                'required',
                File::types(['csv', 'txt'])
                    ->max((int) $this->maxFileSize->asKiloBytes()->value()),
            ],
            ...$columnRules,
        ];
    }

    public function validationAttributes(): array
    {
        return collect($this->requiredColumns)
            ->mapWithKeys(fn (string $column) => ["columnsToMap.{$column}" => strtolower($this->columnLabels[$column] ?? $column)])
            ->toArray();
    }

    public function cancelUpload(): void
    {
        $filename = $this->file?->getFilename();

        $this->resetValidation();

        $this->resetExcept([
            'columnsToMap',
            'columnLabels',
            'requiredColumns',
            'model',
            'importClass',
            'open',
            'importExtras',
        ]);

        if ($filename) {
            $this->removeUpload('file', $filename);
        }
    }

    public function updatedFile(): void
    {
        try {
            $this->validateOnly('file');
        } catch (ValidationException $e) {
            if ($this->file) {
                $this->removeUpload('file', $this->file->getFilename());
            }

            throw $e;
        }

        $csv = $this->readCsv;

        $this->fileHeaders = $csv->getHeader();

        $this->fileRowCount = count($this->csvRecords);

        $this->resetValidation();

        $this->guessWhichColumnsMapToWhichFields();
    }

    public function import()
    {
        $this->validate();

        $import = $this->createImport();

        $batches = collect(
            (new ChunkIterator($this->csvRecords->getRecords(), $this->chunkSize))->get()
        )->map(function ($chunk) use ($import) {
            return (new ImportCsvJob(
                $import,
                $this->model,
                $this->importClass,
                $chunk,
                array_filter($this->columnsToMap),
                Auth::user()->withoutRelations(),
                $this->importExtras,
            ))->delay(now()->addSeconds(2));
        })->toArray();

        /*
         * We are only going to pass the ID into the "finally" closure of the batch so we can
         * successfully query for the import in the case of multi-db multi-tenant systems where
         * the queues are run on a separate db than the tenant imports.
         */
        $importId = $import->id;

        Bus::batch($batches)
            ->finally(function () use ($importId) {
                $import = app(Import::class)->find($importId);

                $import?->touch('completed_at');

                if ($import) {
                    ImportFinishedEvent::dispatch($import);
                }
            })
            ->name("import_{$import->file_name}")
            ->dispatch();

        $this->resetExcept([
            'columnsToMap',
            'columnLabels',
            'requiredColumns',
            'model',
            'importClass',
            'open',
        ]);

        $this->emitTo('csv-imports', 'imports.refresh');
    }

    public function createImport(): Import
    {
        return Auth::user()->imports()->create([
            'file_path' => $this->file->getRealPath(),
            'file_name' => $this->file->getClientOriginalName(),
            'total_rows' => count($this->csvRecords),
            'model' => $this->model,
            'import' => $this->importClass,
        ]);
    }

    protected function readCsv(string $path): Reader
    {
        $stream = fopen($path, 'r');
        $csv = Reader::createFromStream($stream);
        $csv->setHeaderOffset(0);

        return $csv;
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function mount(): void
    {
        $this->columnsToMap = array_fill_keys($this->columnsToMap, '');
    }

    public function render(): View
    {
        return view('laravel-base::livewire.imports.csv-importer');
    }

    public function columnIsRequired(string $column): bool
    {
        return in_array($column, $this->requiredColumns, true);
    }

    protected function guessWhichColumnsMapToWhichFields(): void
    {
        $guesses = collect($this->guesses);

        if ($guesses->isEmpty()) {
            return;
        }

        foreach ($this->fileHeaders as $column) {
            $match = $guesses->search(fn ($options) => in_array(strtolower($column), $options, true));

            if ($match) {
                $this->columnsToMap[$match] = $column;
            }
        }
    }
}
