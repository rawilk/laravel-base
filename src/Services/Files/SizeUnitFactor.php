<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Services\Files;

use Rawilk\LaravelBase\Enums\Filesize as FilesizeEnum;

class SizeUnitFactor
{
    protected array $keys;

    public function __construct()
    {
        $this->keys = array_column(FilesizeEnum::cases(), 'value');
    }

    public function units(): array
    {
        return $this->keys;
    }
}
