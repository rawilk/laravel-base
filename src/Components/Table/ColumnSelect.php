<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Table;

use Rawilk\LaravelBase\Components\BladeComponent;

class ColumnSelect extends BladeComponent
{
    public function __construct(
        public array $columns = [],
        public array $hidden = [],
        public bool $right = true,
    ) {
    }

    public function isHidden(string $column): bool
    {
        return in_array($column, $this->hidden, true);
    }
}
