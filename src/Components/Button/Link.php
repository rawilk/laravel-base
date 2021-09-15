<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Button;

use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Components\BladeComponent;
use Rawilk\LaravelBase\Components\Concerns\HandlesExternalLinks;

class Link extends BladeComponent
{
    use HandlesExternalLinks;

    public function __construct(
        public bool $dark = false,
        public null | string $containerClass = '',
        public null | string $href = null,
        public bool $supportsIcons = false,
        public bool $blockReferrer = false, // Only applies to external links
    ) {
    }

    public function tag(): string
    {
        return $this->href ? 'a' : 'button';
    }

    public function buttonClass(): string
    {
        return Arr::toCssClasses([
            'app-link',
            'relative',
            'flex items-center space-x-2' => $this->supportsIcons,
            'app-link--dark' => $this->dark,
        ]);
    }
}
