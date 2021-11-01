<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Modal;

use Rawilk\LaravelBase\Components\BladeComponent;

class AdvancedFilters extends BladeComponent
{
    public function __construct(
        public $id = 'advanced-filters',
        public $applyClick = 'applyFilters',
        public $resetClick = 'resetFilters',
        public $closeOnApply = true,
        public $closeOnReset = true,
    ) {
    }
}
