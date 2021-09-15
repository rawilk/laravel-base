<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Navigation;

use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Components\BladeComponent;
use Rawilk\LaravelBase\Components\Concerns\HandlesExternalLinks;

class DropdownItem extends BladeComponent
{
    use HandlesExternalLinks;

    public function __construct(public null | string $href = null, public bool $blockReferrer = false, public bool $active = false)
    {
    }

    public function classes(): string
    {
        return Arr::toCssClasses([
            'dropdown-item',
            'w-full' => ! $this->href,
            'space-x-2',
            'dropdown-item--active' => $this->active,
        ]);
    }
}
