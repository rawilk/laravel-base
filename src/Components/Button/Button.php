<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Button;

use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Components\BladeComponent;
use Rawilk\LaravelBase\Components\Concerns\HandlesExternalLinks;

final class Button extends BladeComponent
{
    use HandlesExternalLinks;

    public function __construct(
        public string $variant = 'white',
        public bool $block = false,
        public bool $icon = false,
        public bool $rounded = false,
        public null|string $size = 'md',
        public null|string $href = null,
        public string $containerClass = '',
        public bool $blockReferrer = false, // Only applies to external links
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
