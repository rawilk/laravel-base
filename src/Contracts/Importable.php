<?php

namespace Rawilk\LaravelBase\Contracts;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Rawilk\LaravelBase\Contracts\Models\Import;

interface Importable
{
    public function handle(): void;

    public function usingImport(Import $import): self;

    public function forModel(string $model): self;

    public function forChunk(array $chunk): self;

    public function usingColumns(array $columns): self;

    public function withAuthenticatedUser(?User $user = null): self;
}
