<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Imports;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Rawilk\LaravelBase\Contracts\Importable;
use Rawilk\LaravelBase\Contracts\Models\Import;

class GeneralImport implements Importable
{
    protected Import $import;

    protected string $model;

    protected array $chunk;

    protected array $columns;

    protected ?User $user = null;

    protected array $extras = [];

    public function handle(): void
    {
        $this->processChunk($this->mapChunk());
    }

    protected function processChunk(array $chunk): void
    {
        $affectedRows = $this->model::upsert(
            $chunk,
            ['id'],
            collect($this->columns)->diff('id')->keys()->toArray(),
        );

        if (method_exists($this->import, 'increment')) {
            $this->import->increment('processed_rows', $affectedRows);
        }
    }

    protected function incrementProcessedByChunkSize(): void
    {
        $this->import->increment('processed_rows', count($this->chunk));
    }

    protected function mapChunk(): array
    {
        return collect($this->chunk)
            ->map(function (array $record) {
                return collect($this->columns)
                    ->mapWithKeys(function ($heading, $field) use ($record) {
                        return [$field => $record[$heading] ?? ''];
                    })
                    ->toArray();
            })
            ->toArray();
    }

    public function usingImport(Import $import): self
    {
        $this->import = $import;

        return $this;
    }

    public function forModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function forChunk(array $chunk): self
    {
        $this->chunk = $chunk;

        return $this;
    }

    public function usingColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function withExtras(array $extras): self
    {
        $this->extras = $extras;

        return $this;
    }

    public function withAuthenticatedUser(?User $user = null): self
    {
        $this->user = $user;

        return $this;
    }
}
