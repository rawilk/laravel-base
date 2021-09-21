<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Navigation;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Rawilk\LaravelBase\Components\BladeComponent;

class InnerNav extends BladeComponent
{
    public function __construct(
        public bool $stickyNav = true,
        public $nav = null,
        public $stickyOffset = null,
    ) {
        $this->stickyOffset = $stickyOffset ?? Config::get('laravel-base.components.inner-nav.default_sticky_offset');
    }

    public function navClass(): string
    {
        return Arr::toCssClasses([
            'inner-nav',
            'relative',
            'space-y-1',
            "md:sticky {$this->stickyOffset}" => $this->stickyNav,
        ]);
    }

    public function contentClass(): string
    {
        return Arr::toCssClasses([
            'inner-nav-content',
            'lg:col-span-9',
            'space-y-6',
            'lg:px-0',
            'sm:px-6' => ! $this->stickyNav,
        ]);
    }
}
