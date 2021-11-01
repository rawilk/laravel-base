<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Elements;

use Illuminate\Support\Collection;
use Rawilk\LaravelBase\Components\BladeComponent;

class TooltipHelp extends BladeComponent
{
    public function __construct(
        public string $placement = '',
        public null|string $title = '',
        public null|string|array|Collection $triggers = null,
        public string $iconHeight = 'h-4',
        public string $iconWidth = 'w-4',
    ) {
    }
}
