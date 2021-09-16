<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Elements;

use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Components\BladeComponent;

class Badge extends BladeComponent
{
    public function __construct(
        public string $variant = 'gray',
        public bool $large = false,
        public bool $rounded = false,
        public bool $removeable = false,
        public bool $dot = false,
    ) {
    }

    public function classes(): string
    {
        return Arr::toCssClasses([
            'badge',
            "badge--{$this->variant}" => $this->variant,
            'inline-flex items-center py-0.5',
            'rounded' => $this->rounded,
            'rounded-full' => ! $this->rounded,
            'badge--large px-3 text-sm leading-5' => $this->large,
            'px-2.5 text-xs leading-4' => ! $this->large,
        ]);
    }
}
