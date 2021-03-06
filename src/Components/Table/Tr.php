<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Table;

use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Components\BladeComponent;

class Tr extends BladeComponent
{
    public function __construct(
        public null|string $role = 'row',
        public bool|string|int $tabIndex = false,
        public bool|string|int $rowIndex = false,
        public bool $striped = true,
        public bool $selected = false,
        public bool $wireLoads = false, // Automatically add a wire:loading directive
    ) {
    }

    public function classes(): string
    {
        return Arr::toCssClasses([
            'odd:bg-white even:bg-slate-50' => $this->striped && ! $this->selected,
            'bg-orange-100' => $this->selected,
        ]);
    }
}
