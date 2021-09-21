<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Navigation;

use Illuminate\Support\Arr;
use Rawilk\LaravelBase\Components\BladeComponent;

class InnerNavItem extends BladeComponent
{
    public bool $active;

    public function __construct(
        public string $href = '#',
        public $icon = false,
        null|bool $active = null,
    ) {
        $this->active = is_null($active)
            ? request()->fullUrlIs($href)
            : $active;
    }

    public function linkClass(): string
    {
        return Arr::toCssClasses([
            'inner-nav-link',
            'relative',
            'group space-x-3 rounded-md px-3 py-2 flex items-center text-sm leading-5 font-medium focus:outline-blue-gray transition-colors',
            'active text-blue-500 bg-blue-gray-200' => $this->active,
            'text-cool-gray-500 hover:text-cool-gray-600 hover:bg-blue-gray-200 focus:text-cool-gray-600 focus:bg-blue-gray-200' => ! $this->active,
        ]);
    }

    public function iconClass(): string
    {
        return Arr::toCssClasses([
            'flex-shrink-0 h-6 w-6 transition-colors',
            'text-blue-500' => $this->active,
            'text-cool-gray-400 group-hover:text-cool-gray-500 group-focus:text-cool-gray-500' => ! $this->active,
        ]);
    }
}
