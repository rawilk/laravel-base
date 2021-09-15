<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Button;

use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Components\BladeComponent;

final class Button extends BladeComponent
{
    public function __construct(
        public string $variant = 'white',
        public bool $block = false,
        public bool $icon = false,
        public bool $rounded = false,
        public null|string $size = 'md',
        public null|string $href = null,
        public string $containerClass = '',
        public $extraAttributes = '',
    ) {
    }

    public function buttonClass(): string
    {
        return Arr::toCssClasses([
            'button',
            'relative',
            'w-full button--block' => $this->block,
            'button--icon' => $this->icon,
            'rounded-full' => $this->rounded,
            "button--{$this->variant}",
            "button--{$this->size}" => $this->size,
        ]);
    }

    public function tag(): string
    {
        return $this->href ? 'a' : 'button';
    }
}
