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
        public bool $appLink = true,
        public bool $dark = false,
        public bool $supportsIcons = true,
        public bool $blockReferrer = false, // Only applies to external links
        public bool $hideExternalIndicator = false, // Only applies to external links
    ) {
    }

    public function classes(): string
    {
        return Arr::toCssClasses([
            'app-link' => $this->appLink,
            'app-link--dark' => $this->appLink && $this->dark,
            'inline-flex items-center space-x-2' => $this->appLink && $this->supportsIcons,
            'no-external-indicator' => $this->hideExternalIndicator,
        ]);
    }
}
