<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Navigation;

use Rawilk\LaravelBase\Components\BladeComponent;

/**
 * ActionMenu component is meant to provide a quick, consistent dropdown interface
 * for use in tables.
 */
class ActionMenu extends BladeComponent
{
    public function __construct(
        public string $icon = 'css-more-vertical-alt',
        public bool $fixed = true,
        public bool $right = true,
        public $id = false,
    ) {
    }
}
