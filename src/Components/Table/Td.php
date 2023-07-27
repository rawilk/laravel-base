<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Table;

use Rawilk\LaravelBase\Components\BladeComponent;

class Td extends BladeComponent
{
    public function __construct(
        public bool $hidden = false,
        public bool|string|int $colIndex = false,
        public ?string $role = 'cell',
    ) {
    }
}
