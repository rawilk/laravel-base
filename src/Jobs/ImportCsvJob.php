<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rawilk\LaravelBase\Contracts\Importable;
use Rawilk\LaravelBase\Contracts\Models\Import;

class ImportCsvJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public function __construct(
        public Import $import,
        public string $model,
        public string $importClass,
        public array $chunk,
        public array $columns,
        public ?User $user = null,
        public ?array $importExtras = null,
    ) {
    }

    public function handle(): void
    {
        if ($this->batch()->canceled()) {
            return;
        }

        $importClass = new $this->importClass;
        if (! $importClass instanceof Importable) {
            $this->batch()->cancel();

            return;
        }

        $importClass->usingImport($this->import)
            ->forModel($this->model)
            ->forChunk($this->chunk)
            ->usingColumns($this->columns)
            ->withAuthenticatedUser($this->user)
            ->withExtras($this->importExtras ?? [])
            ->handle();

        sleep(1);
    }
}
