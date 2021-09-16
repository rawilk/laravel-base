<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Lists;

use Rawilk\LaravelBase\Components\BladeComponent;

class ActionItem extends BladeComponent
{
    public function __construct(
        public null | string $href = '#',
        public $description = null,
        public $icon = null,
        public string $iconClass = 'bg-blue-50 text-blue-700 ring-white',
        public $before = null, // slot
        public $extra = null, // slot
    ) {
    }
}
