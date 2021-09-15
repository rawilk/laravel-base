<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Navigation;

use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Components\BladeComponent;
use Rawilk\LaravelBase\Components\Concerns\HandlesExternalLinks;

class Link extends BladeComponent
{
    use HandlesExternalLinks;

    public function __construct(
        public null | string $href = '#',
        public bool $dark = false,
        public bool $supportsIcons = true,
        public bool $blockReferrer = false, // Only applies to external links
    ) {
    }

    public function classes(): string
    {
        return Arr::toCssClasses([
            'app-link',
            'app-link--dark' => $this->dark,
            'inline-flex items-center space-x-2' => $this->supportsIcons,
        ]);
    }
}
