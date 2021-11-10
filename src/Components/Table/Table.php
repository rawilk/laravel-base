<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Table;

use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Components\BladeComponent;

class Table extends BladeComponent
{
    public function __construct(
        public bool $border = false,
        public bool $rounded = true,
        public bool $shadow = true,
        public $head = null,
        public $tbody = null, // Meant only for slot access to assign attributes to the tbody
        public $containerClass = null,
    ) {
    }

    public function tbodyClasses(): string
    {
        return Arr::toCssClasses([
            'bg-white divide-y divide-blue-gray-200' => $this->border,
        ]);
    }
}
