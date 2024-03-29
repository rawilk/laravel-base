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
        bool $active = null,
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
            'group rounded-md px-3 py-2 flex items-center text-sm leading-5 font-medium focus:outline-slate transition-colors',
            'active text-blue-500 bg-slate-200 dark:text-blue-600 dark:bg-slate-600' => $this->active,
            'text-gray-500 hover:text-gray-600 hover:bg-slate-200 focus:text-gray-600 focus:bg-slate-200 dark:text-gray-300 dark:hover:bg-slate-500 dark:focus:bg-slate-500' => ! $this->active,
        ]);
    }

    public function iconClass(): string
    {
        return Arr::toCssClasses([
            'flex-shrink-0 h-6 w-6 transition-colors mr-3',
            'text-blue-500 dark:text-blue-400' => $this->active,
            'text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500 dark:group-hover:text-gray-600 dark:group-focus:text-gray-600' => ! $this->active,
        ]);
    }
}
