<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Events\Imports;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Rawilk\LaravelBase\Contracts\Models\Import;

class ImportFinishedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Import $import, public ?array $extras = null)
    {
    }
}
